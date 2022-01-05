#!/usr/bin/env python
""" Script inserting data from .csv files to database."""

import sys
import pickle
import numpy as np
import pandas as pd
import argparse
import cx_Oracle
import os
from pathlib import Path
from typing import List, Dict, Union, Callable, Optional
from dataclasses import dataclass, field
from datetime import datetime

sys.path.insert(0, str(Path(__file__).absolute().parent.parent))

from scripts.utils import DBConfig, PathLike, load_toml_config, execute_sql

NameMapper = Union[Callable[[str], str], Dict[str, str]]

@dataclass
class CSVData():
    """Describes CSV data files that contain the same kind of data
    and can be parsed and inserted to the table together.

    CSV files will be parsed into dataframe and concatenated,
    after which their fields may need to be renamed to satisfy naming restrictions of oracle bind variables (e.g. no white spaces and brackets).

    Fields order should correnspond to order of columns to which data will
    be inserted. If columns are not given, order of columns definition
    matters and number of fields from which insertion is made must equals
    the number of columns in the table.

    For large data there may be a need to sample only its fraction.
    Sampling of multiple data files and concatenating those samples may
    takes significant amount of time, so there is an option of creating
    cache CSV file with such sampled data. Once the cache file is created
    the data may be loaded faster.

    Args:
        -paths: Path to a file or a directory.
        -table_name: Name of the table data will be inserted into.
        -fields: List of names of fields (after eventual renaming) 
        to be inserted.
        -datetime_fields: Dictionary matching datetime format with list 
        of fields in that format.
        -renamings: List of mappers (functions or dictionaries) that will be 
        successively passed to the pd.DataFrame.rename function to rename
        fields' names.
        -columns: List of names of columns into which data will be inserted
        from respective fields.
        -cache_path: Path to the cache file that will be created before
        loading data with cache."""

    path: Path
    table_name: str
    fields: List[str]
    datetime_fields: Dict[str, List[str]] = field(default_factory=dict)
    renamings: List[NameMapper] = field(default_factory=list)
    columns: Optional[List[str]] = None
    cache_path: Optional[Path] = None

    def _concatenate_files(
        self,
        file_paths: List[PathLike],
        size_limit: Optional[float],
        reset_cache: Optional[bool],
    ) -> pd.DataFrame:
        sizes = []
        sum_of_file_sizes = sum(
            [os.path.getsize(path) for path in file_paths]
        ) / 1024 ** 2
        frac = size_limit / sum_of_file_sizes if size_limit else 1
        temp_file = "df_all.pkl"
        if os.path.exists(temp_file):
            os.remove(temp_file)
        lengths = set()
        with open(temp_file, 'ab') as f:
            for path in file_paths:
                print(f"Reading {path}")
                df = pd.read_csv(path)
                df = df.sample(frac=frac)

                pickle.dump(df,f)
                sizes.append(len(df))
                n_cols = len(df.columns)
                lengths.add(n_cols)
 
        with open(temp_file,'rb') as f:
            df_all = pickle.load(f)
            offset = len(df_all)
            rest = np.empty(sum(sizes[1:]) * n_cols).reshape(-1, n_cols)
            df_all = df_all.append(pd.DataFrame(rest, columns=df_all.columns))
            for size in sizes[1:]:
                df = pickle.load(f)
                df_all.iloc[offset:offset+size] = df.values
                offset += size

        os.remove(temp_file)
        if reset_cache:
            df_all.to_csv(self.cache_path)
        return df_all

    def _get_raw_dataframe(
        self, 
        size_limit: Optional[float],
        use_cache: Optional[bool],
        reset_cache: Optional[bool]
    ) -> pd.DataFrame:
        if use_cache and not os.path.exists(self.cache_path):
            reset_cache = True
        if use_cache and not reset_cache:
            print(f"Reading from cache: {self.cache_path}")
            return pd.read_csv(self.cache_path)

        file_paths = [self.path]
        if os.path.isdir(self.path):
            file_paths = list(Path(self.path).glob("**/*"))
        df = self._concatenate_files(
            file_paths,
            size_limit,
            reset_cache
        )
        return df

    def _rename_dataframe_columns(self, df: pd.DataFrame):
        for mapper in self.renamings:
            df.rename(mapper, axis=1, inplace=True)

    def _drop_null_values(self, df: pd.DataFrame):
        old_len = len(df)
        df.dropna(how="any", inplace=True)
        print(f"{old_len - len(df)} " + 
               "rows were dropped due to having NaN values.")

    def _cast_datetime_columns(self, df: pd.DataFrame) -> pd.DataFrame:
        column_formats = {
            col: format
            for format, columns in self.datetime_fields.items()
            for col in columns
        }
        for col, format in column_formats.items():
            df[col] = df[col].map(lambda s: datetime.strptime(s, format))
        return df

    def _cast_types(self, df: pd.DataFrame) -> pd.DataFrame:
        df = df.astype(str, copy=False)
        df = self._cast_datetime_columns(df)
        return df

    def get_dataframe(
        self,
        size_limit: Optional[float],
        use_cache: Optional[bool],
        reset_cache: Optional[bool]
    ) -> pd.DataFrame:
        df = self._get_raw_dataframe(size_limit, use_cache, reset_cache)
        self._rename_dataframe_columns(df)
        df = df[self.fields]
        self._drop_null_values(df)
        df = self._cast_types(df)
        return df


DATA_PATH = Path(__file__).parent.parent / "data"

OPENFLIGHTS_PATH = DATA_PATH / "openflights"


OPENFLIGHTS_AIRPORT_DATA = CSVData(
    path = OPENFLIGHTS_PATH / "airports.csv",
    table_name = "Airport",
    fields = [
        "ICAO",
        "Name",
        "City",
        "Country",
        "Latitude",
        "Longitude"
    ]
)


OPENFLIGHTS_AIRLINE_DATA = CSVData(
    path = OPENFLIGHTS_PATH / "airlines.csv",
    table_name = "Airline",
    fields = [
        "ICAO",
        "Name",
        "Country"
    ],
    columns = [
        "id",
        "airline_name",
        "country"
    ]
)

EUROCONTROL_PATH = DATA_PATH / "eurocontrol"

EUROCONTROL_FLIGHT_DATETIME_FIELDS = [
    "FILED_OFF_BLOCK_TIME",
    "FILED_ARRIVAL_TIME", 
    "ACTUAL_ARRIVAL_TIME",
    "ACTUAL_OFF_BLOCK_TIME"
]


EUROCONTROL_FLIGHT_DATA =  CSVData(
    path = EUROCONTROL_PATH / "flights",
    table_name = "Flight",
    fields = [
        "ECTRL_ID", 
        "ADEP", 
        "ADES"
    ] + EUROCONTROL_FLIGHT_DATETIME_FIELDS + [
        "AC_Operator",
        "Actual_Distance_Flown"
    ],
    datetime_fields = {
        "%d-%m-%Y %H:%M:%S": EUROCONTROL_FLIGHT_DATETIME_FIELDS
    },
    renamings = [
        lambda name: name.replace(' ', '_'),
        {"Actual_Distance_Flown_(nm)": "Actual_Distance_Flown"}
    ],
    cache_path = EUROCONTROL_PATH / "cache" / "flights.csv"
)

def get_insert_command(
    table_name: str,
    values: List[str],
    columns: List[str] = None
) -> str:
    """Returns sql command inserting data to table.
    Args:
        - table_name: Name of table to which data will be inserted.
        - values: List of names of bind variables used in insertion command.
        - columns: List of table's columns to which data will be inserted
        from bind variables. If None, data from bind variables is inserted to first n columns of the table where n is a length of values list."""

    def trim_last_string(l: List[str]):
        l[-1] = l[-1].replace(", ", "")
    
    value_strings = list(map(lambda val: ":" + val + ", ", values))
    trim_last_string(value_strings)
    values_string = " VALUES (" + "".join(value_strings) + ")"
    
    columns_string = ""
    if columns:
        column_strings = list(map(lambda col: col + ", ", columns))
        trim_last_string(column_strings)
        columns_string = " (" + "".join(column_strings) + ")"
    
    res = "INSERT INTO " + table_name + columns_string + values_string
    return res

def insert_to_table(
        connection: cx_Oracle.Connection,
        csv_data: CSVData,
        size_limit: Optional[float] = None,
        use_cache: Optional[bool] = None,
        reset_cache: Optional[bool] = None
    ):
    """Inserts data from csv file to table.
    Args:
        -connection: cx_Oracle.Connection object
        -csv_data: contains information about data files, table and its columns
        to which data will be inserted."""

    cursor = connection.cursor()
    df = csv_data.get_dataframe(size_limit, use_cache, reset_cache)
    command = get_insert_command(
        csv_data.table_name,
        csv_data.fields,
        csv_data.columns
    )
    print(f"Inserting to {csv_data.table_name} from {csv_data.path}")
    cursor.executemany(command, df.to_dict("records"), batcherrors=True)

    batch_errors = cursor.getbatcherrors()
    n_inserted = len(df) - len(batch_errors)
    print(f"Successfully inserted {n_inserted} rows." +
          f"({round(n_inserted * 100/ len(df), 2)} %)")
    print(f"{len(batch_errors)} rows were not inserted. " + 
          f"({round((len(df) - n_inserted) * 100 / len(df), 2)} %)")
    MAX_N_ROWS_TO_LOG = 5
    for error in batch_errors[:MAX_N_ROWS_TO_LOG]:
        print("Error", error.message, "at row offset", error.offset)
    print("...\n" if len(batch_errors) > 5 else "")

    connection.commit()

def initialize(db_config: DBConfig):
    print("Initializing database.\n")
    execute_sql(db_config, "./tables/loty.sql")

def insert(
    db_config: DBConfig,
    flights_size_limit: Optional[float],
    flights_reset: bool
):
    cx_Oracle.init_oracle_client(lib_dir=None)
    con = cx_Oracle.connect(
        db_config.username,
        db_config.password,
        db_config.dsn
    )

    insert_to_table(con, OPENFLIGHTS_AIRPORT_DATA)
    insert_to_table(con, OPENFLIGHTS_AIRLINE_DATA)
    insert_to_table(
        con, 
        EUROCONTROL_FLIGHT_DATA, 
        size_limit=flights_size_limit,
        use_cache=True,
        reset_cache=flights_reset
    )

if __name__ == "__main__":
    parser = argparse.ArgumentParser(
        description="Initialize database and inserts data into it.",
        formatter_class=argparse.RawTextHelpFormatter
    )
    parser.add_argument(
        "-i", "--init_only", 
        action="store_true",
        help="Execute tables/loty.sql only. Don't insert data to the database."
    )
    parser.add_argument(
        "--flights_size",
        type=float,
        default=15,
        help=("Determines size of flights data to be loaded before\n" +
              "processing and insertion to the table. 10MiB as default."
        )
    )
    parser.add_argument(
        "--flights_reset",
        action="store_true",
        help=("Create new flights data cache file.")
    )
    args = parser.parse_args()

    db_config: DBConfig = load_toml_config()
    initialize(db_config)
    if not args.init_only:
        insert(db_config, args.flights_size, args.flights_reset)