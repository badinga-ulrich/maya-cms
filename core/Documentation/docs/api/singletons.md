# Singletons API


## Get singleton data.

#### Endpoint

```sh
/api/singletons/get/{singletonname}
```

#### Example

```javascript
fetch('/api/singletons/get/{singletonname}?token=xxtokenxx')
    .then(data => data.json())
    .then(data => console.log(data));
```

## Get all singletons

#### Endpoint

```sh
/api/singletons/listSingletons
```

#### Example

```javascript
fetch('/api/singletons/listSingletons?token=xxtokenxx')
    .then(singletons => singletons.json())
    .then(singletons => console.log(singletons));
```
