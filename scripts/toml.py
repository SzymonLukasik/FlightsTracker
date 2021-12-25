import toml
import pathlib

from typing import Any, Dict, Optional
from scripts.utils import PathLike



def load_toml_config(toml_path: Optional[PathLike] = None) -> Dict[str, Any]:
    """Load toml config from path if provided otherwise use defualt path."""
    root = get_root_dir_of_repo()
    toml_example_path = root / "config_example.toml"

    if toml_path is None:
        toml_path = root / "config.toml"

    if not pathlib.Path(toml_path).exists():
        raise RuntimeError(
            f"Configuration file {toml_path} not found."
            f" See {toml_example_path} for an example configuration."
        )
    return toml.load(toml_path)