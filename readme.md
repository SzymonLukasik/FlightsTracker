# FlightsTracker

## Inserting data
Before running python scripts on students servers it's recomended to set env variable OPENBLAS_NUM_THREADS to 10 or lower number. Otherwise python may crash during importing pandas.
<code>export OPENBLAS_NUM_THREADS=10</code>
### Configuration
- Install pandas if not installed: <code>pip install pandas</code>
- Copy <code>./config_example.toml</code> file, rename it to <code>./config.toml</code> and enter your username and password.
### Usage of <code>./scripts/insert_data.py</code> script
Being in the root directory:
- to only execute <code>./tables/loty.sql</code> script, run: <code>python3 ./scripts/insert_data.py -i</code>
- to insert data to the database, run: <code>python3 ./scripts/insert_data.py</code>
- to insert data to the database after creating new flights cache file, with given limit on the size of data to be loaded, run: <code>python3 ./scripts/insert_data.py --flights_size FLIGHTS_SIZE --flights_reset</code> 
- You don't have to specify the limit, the default limit is 10MiB. <code>python3 ./scripts/insert_data.py --flights_reset</code>

There is already flights cache file created from all 20 availible flights csv files from eurocontrol that were uniformly sampled.

Execution of <code>python3 ./scripts/insert_data.py</code> should take about 10MiB (out of 15MiB) of space quota available for user in the oracle database on students server.

Depending on the state of the database before execution, the <code>./tables/loty.sql</code> script may fail, any output and errors are saved in the <code>./scripts/shell_log.txt</code> file.

### Usage of <code>./scripts/execute_sql.sh</code> script.
To execute any sql script on your database, from the root directory run: <code>./scripts/execute_sql.sh USERNAME PASSWORD "//labora.mimuw.edu.pl/LABS" SCRIPT_PATH</code>
Any output and errors are saved in the <code>./scripts/shell_log.txt</code> file.