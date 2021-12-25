#!/usr/bin/env python
""" Script inserting data from eurocontrol's Flights*.csv files to database."""

import sys
import pandas as pd
import cx_Oracle

from pathlib import Path

def main(
    cx_Oracle.init_oracle_client(lib_dir=None)
    con = cx_Oracle.connect(db_config.user, db_config.pw, db_config.dsn)
        
)

