import sys
import pickle
import numpy as np
import pandas as pd
import os
from pathlib import Path
from typing import List, Dict, Union, Callable, Optional
from dataclasses import dataclass, field
from datetime import datetime

sys.path.insert(0, str(Path(__file__).absolute().parent.parent))

from scripts.utils import PathLike

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

        with open(temp_file, 'ab') as f:
            for path in file_paths:
                print(f"Reading {path}")
                df = pd.read_csv(path)
                df = df.sample(frac=frac)

                pickle.dump(df,f)
                sizes.append(len(df))
                n_cols = len(df.columns)
 
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
