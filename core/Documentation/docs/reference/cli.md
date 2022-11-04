# CLI Tools
Maya comes with a CLI containing useful commands to interract from the command line.


## List of commands:

### ./maya export

Export all data from maya to a directory

_Arguments_

`--target`: The destination directory for the export of the maya data

Possible subcommands to export only certain types of data:

- `./maya export/accounts`
- `./maya export/assets`
- `./maya export/tmp`
- `./maya export/collections`
- `./maya export/singletons`
- `./maya export/forms`

### ./maya import

Imports data from a folder. **It expects the structure of a `./maya export` result**.

_Arguments_

`--src`: the source folder containing the maya data to import

Possible subcommands to import only certain types of data:

 - `./maya import/accounts`
 - `./maya import/assets`
 - `./maya import/tmp`
 - `./maya import/collections`
 - `./maya import/singletons`
 - `./maya import/forms`


### ./maya flush

Deletes all the data in maya (from the db)

Possible subcommands to flush only certain types of data:

- `./maya flush/accounts`
- `./maya flush/assets`
- `./maya flush/tmp`
- `./maya flush/collections`
- `./maya flush/singletons`
- `./maya flush/forms`


### ./maya create-lang

Create language file for the admin ui in `config/maya/i18n`.

_Arguments_

`--lang`: target language

### ./maya install/addon

Pulls addon zip packager from url or github repo name. 

_Arguments_
`--url`: url 
`--name`: repository name belonging to agentejo organization on github

For _example_ if we wanted to download Lokalize addon from github we would need to run following command:
```
./maya install/addon --name Lokalize
```

### ./maya account/create

Creates new Cockpit user

_Arguments_
`--user`: username
`--email`: email
`--passwd`: plain password


### ./maya account/generate-password

Encodes provided password and returns its hash.

_Arguments_
`--passwd`: plain password

### ./maya jobs/start-runner

Starts job runner for given task id.

_Arguments_
`idle`: task id

### ./maya jobs/stop-runner

Stops job runner

### ./maya collection/renamefield

Rename collection field in it's entries

_Arguments_

`--collection`: collection name
`--field`: field name
`--newfield` new field name


## ./maya collection/removefield

Remove collection field in it's entries

_Arguments_

`--collection`: collection name
`--field`: field name