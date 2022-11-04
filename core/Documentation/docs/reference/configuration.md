
# Configuration

By default, Maya doesn't need any further configuration to run. However, you might want to manage multi-language content or use MongoDB instead of SQLite as your favorite data storage. Therefore Maya provides an easy way to tweak some settings.

## Possible settings:

```yaml
app.name: 'My Project X'
session.name: mysession
sec-key: xxxxx-SiteSecKeyPleaseChangeMe-xxxxx
site_url: 'https://mydomain.com'
# cockpit default language
i18n: de

# define the languages you want to manage
languages:
    fr: French
    de: German

# define allowed file types for uploads (global)
allowed_uploads: pdf, png, jpg, jpeg, svg, gif

# define a custom logo
logo:
    url: "#uploads:2022/10/08/logo_uid_5dc548b41c0cd.png"
    width: 8em    # optional, default: 30px
    height: 3em   # optional, default: 30px
    hideName: true    # hide app name

database:
    server: 'mongodb://localhost:27017'
    options: { db: mayadb }

# SMTP settings
mailer:
    from: info@mydomain.tld
    from_name : John Doe
    transport: smtp
    host: smtp.myhost.tld
    user: username
    password: xxpasswordxx
    port: 25
    auth: true
    encryption: ssl # '', ssl, tls or starttls
    reply_to  : john.doe@mydomain.tld
    cc        :
        - mail1@example.com
        - mail2@example.com
    bcc       :
        - mail1@example.com
        - mail2@example.com


```

## Language settings

Maya always uses a 'default' language, but setting a default language name is optional.

* When should you consider setting a default language?
    * if using localized multilingual content
    * because the Language selection dropdown would become confusing
* Why should you set a default language?
    * you can get rid of misleading "default" option in content Language selection dropdown
* How will multiple languages affect my access to content of localized Fields?
    * using multiple languages will create additional content for localized Fields
* How can I reference localized Fields?
    * with their corresponding `fieldName`
    * with their corresponding `fieldName` and appended language suffix: `fieldName_fr` or `fieldName_de`

## Logo

Customize logo and colours 


### Configuration

Upload an image, copy it's url and set a config variable

```yaml
logo:
    url: "#uploads:2022/10/08/logo_uid_5dc548b41c0cd.png"
    width: 8em    # optional, default: 30px
    height: 3em   # optional, default: 30px
    hideName: true    # hide app name

```

### Logo format

The logo url can be in the following formats

* internal paths (my favourite, because it works on local and production host without changes)
  * `#uploads:2019/11/08/5dc548b41c0cdlogo.png`
  * `assets:app/media/icons/code.svg`

* full url
  * `https://example.com/storage/uploads/2019/11/08/5dc548b41c0cdlogo.png`
  * `https://example.com/logo.png`

* relative path to site url
  * `/logo.png`

* full path
  * `/var/www/virtual/username/html/storage/uploads/2019/11/08/5dc548b41c0cdlogo.png`
  * `/var/www/virtual/username/html/logo.png`




## Database

### Use SQLITE

By default Maya use Sqlite, If you want to use Sqlite as database, juste remove `database` from configuration.

### Use MongoDB

If you want to use MongoDB as database, make sure you have [MongoDB PHP Driver](http://php.net/manual/en/set.mongodb.php) by 
install mongodb extension available from [PECL](http://php.net/manual/en/mongodb.installation.pecl.php). You will want to run Cockpit installation again after change database configuration to mongodb.

#### Usage

```yaml
database:
    server: 'mongodb://localhost:27017'
    options: { db: mayadb }
```