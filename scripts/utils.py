import os
import toml
import subprocess

from typing import Optional, Union
from pathlib import Path
from dataclasses import dataclass

PathLike = Union[str, os.PathLike]

def get_root_dir_of_repo() -> Path:
    """Returns absolute path to the root directory of the code repository."""
    # parent ot this file is scripts/, parent.parent is the root.
    return Path(__file__).parent.parent.resolve()

@dataclass
class DBConfig:
    username: str
    password: str
    dsn: str

def load_toml_config(toml_path: Optional[PathLike] = None) -> DBConfig:
    """Load toml config from path if provided otherwise use defualt path."""
    root = get_root_dir_of_repo()
    toml_example_path = root / "config_example.toml"

    if toml_path is None:
        toml_path = root / "config.toml"

    if not Path(toml_path).exists():
        raise RuntimeError(
            f"Configuration file {toml_path} not found."
            f" See {toml_example_path} for an example configuration."
        )

    config_dict = toml.load(toml_path)
    return DBConfig(**config_dict)

def execute_sql(db_config: DBConfig, script_path: PathLike):
    exec_script_path = str(
        Path(__file__).absolute().parent / "execute_sql.sh"
    )
    subprocess.run([
        exec_script_path,
        db_config.username,
        db_config.password,
        db_config.dsn,
        script_path
    ])
