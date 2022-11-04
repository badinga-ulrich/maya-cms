# Maya API

## Authenticate user

#### Endpoint
```sh
/api/maya/authUser
```

#### Example

```javascript
fetch('/api/maya/authUser?token=xxtokenxx', {
    method: 'post',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        user: 'username',
        password: 'xxpasswordxx'
    })
})
.then(user => user.json())
.then(user => console.log(user));
```

## Create / Update user

#### Endpoint
```sh
/api/maya/saveUser
```

#### Example

```javascript
fetch('/api/maya/saveUser?token=xxtokenxx', {
    method: 'post',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        user: {...} // user data (user, name, email, active, group)
    })
})
.then(user => user.json())
.then(user => console.log(user));
```

## Get users.

#### Endpoint
```sh
/api/maya/listUsers
```

#### Example

```javascript
fetch('/api/maya/listUsers?token=xxtokenxx', {
    method: 'post',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        filter: {...}
    })
})
.then(users => users.json())
.then(users => console.log(users));
```

## Get assets

#### Endpoint
```sh
/api/maya/assets
```

#### Example

```javascript
fetch('/api/maya/assets?token=xxtokenxx', {
    method: 'post',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        filter: {...}
    })
})
.then(assets => assets.json())
.then(assets => console.log(assets));
```

## Get thumbnail url

#### Endpoint
```sh
/api/maya/image
```

#### Example

```javascript
fetch('/api/maya/image?token=xxtokenxx', {
    method: 'post',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        src: imagePath || asset._id,
        m: ('thumbnail' | 'bestFit' | 'resize' | 'fitToWidth' | 'fitToHeight'),
        f: (array),         // filter name(s), one or more of: 'blur' | 'brighten' | 'colorize' | 'contrast' | 'darken' | 'desaturate' | 'edge detect' | 'emboss' | 'flip' | 'invert' | 'opacity' | 'pixelate' | 'sepia' | 'sharpen' | 'sketch'
        w: (int),           // width
        h: (int),           // height
        q: (int),           // quality
        d: (boolean),       // include full domain path
        b64: (boolean),     // return base64 encoded image string
        o : (boolean)       // out the image instead of the url from which the image can be fetched

    })
})
.then(url => url.text())
.then(url => console.log(url));
```

```html
<img src="/api/maya/image?token=xxtokenxx&src=path&w=200&h=200&f[brighten]=25&o=true" />
```