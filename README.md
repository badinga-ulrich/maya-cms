### Requirements

* PHP >= 7.3
* PDO + SQLite (or MongoDB)
* GD extension
* mod_rewrite, mod_versions enabled (on apache)

make also sure that <code>$_SERVER['DOCUMENT_ROOT']</code> exists and is set correctly.


### Installation

1. Download Maya and put the maya folder in the root of your web project
2. Make sure that the __/maya/storage__ folder and all its subfolders are writable
3. Go to __/maya/install__ via Browser
4. You're ready to use Maya :-)


### Build (Only if you modify JS components)

You need [nodejs](https://nodejs.org/) installed on your system.

First run `npm install` to install development dependencies

1. Run `npm run build` - For one-time build of styles and components
2. Run `npm run watch` - For continuous build every time styles or components change


### Dockerized Development

You need docker installed on your system: https://www.docker.com.

1. Run `npm run docker-init` to build the initial image.
2. Run `npm run docker` to start an Apache environment suited for Maya on port 8080 (this folder mapped to /var/www/html).


## 💐 Partners
[![Partners](https://img.shields.io/badge/Parteners-0-acf)](#partners)

## 💐 Sponsors

[![Backers](https://opencollective.com/maya/backers/badge.svg)](#backers) [![Sponsors](https://opencollective.com/maya/sponsors/badge.svg)](#sponsors)


## Copyright and license

Copyright since 2022 [Badinga Ulrich](https://badinga-ulrich.github.io/) under the MIT license.

See [LICENSE](LICENSE) for more information.
