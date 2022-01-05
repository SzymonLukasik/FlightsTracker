#!/usr/bin/env python
""" Script inserting data from .csv files to database."""

import sys
import argparse
import cx_Oracle
from pathlib import Path
from typing import List, Optional

sys.path.insert(0, str(Path(__file__).absolute().parent.parent))

from scripts.utils import DBConfig, PathLike, load_toml_config, execute_sql

from scripts.csvdata import CSVData

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