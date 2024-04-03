# BIIGLE KPIs Module

[![Test status](https://github.com/biigle/kpis/workflows/Tests/badge.svg)](https://github.com/biigle/kpis/actions?query=workflow%3ATests)

A BIIGLE module to collect key performance indicators.

## Installation

1. Run `composer require biigle/kpis`.
2. Add the `KPIS_TOKEN` variable to the `.env` file. The value is an authentication token (e.g. generated with `pwgen 30 1`).
3. Copy the [Bash script](src/resources/scripts/countRequests.sh) to your webserver, configure the authentication token (and maybe the base URL) inside the script and set up a daily cron job that executes the script with the gzipped webserver logfile of the previous day as argument. Example:
   ```
   30 0 * * * /path/to/countRequests.sh /path/to/logfiles/$(/bin/date -Idate --date "1 day ago").sql.gz > /path/to/countRequests.log 2>&1
   ```
4. Run the migrations.

## Developing

Take a look at the [development guide](https://github.com/biigle/core/blob/master/DEVELOPING.md) of the core repository to get started with the development setup.

Want to develop a new module? Head over to the [biigle/module](https://github.com/biigle/module) template repository.

## Contributions and bug reports

Contributions to BIIGLE are always welcome. Check out the [contribution guide](https://github.com/biigle/core/blob/master/CONTRIBUTING.md) to get started.
