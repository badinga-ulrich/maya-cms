# Collection


## Get all collections

#### Endpoint

```sh
/api/collections/listCollections
```

#### Example

```javascript
fetch('/api/collections/listCollections?token=xxtokenxx')
    .then(collections => collections.json())
    .then(collections => console.log(collections));
```

## Get collection schema

#### Endpoint

```sh
/api/collections/collection/{collectionname}
```

#### Example

```javascript
fetch('/api/collections/collection/posts?token=xxtokenxx')
    .then(collection => collection.json())
    .then(collection => console.log(collection));
```

## Update collection schema

#### Endpoint

```sh
/api/collections/updateCollection/{collectionname}
```

#### Example

```javascript
fetch('/api/collections/updateCollection/posts?token=xxtokenxx', {
        method: 'post',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            data: {
            fields: [...]
          }
        })
    })
    .then(collection => collection.json())
    .then(collection => console.log(collection));
```

## Get collection entries

#### Endpoint

```sh
/api/collections/get/{collectionname}
```

#### Example

```javascript
fetch('/api/collections/get/posts?token=xxtokenxx')
    .then(res => res.json())
    .then(res => console.log(res));
```

```javascript
fetch('/api/collections/get/posts?token=xxtokenxx', {
    method: 'post',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        filter: {published:true},
        fields: {fieldA: 1, fieldB: 1},
        limit: 10,
        skip: 5,
        sort: {_created:-1},
        populate: 1, // resolve linked collection items
        simple: 1, // Get result without collection's shema

        lang: 'de' // return normalized language fields (fieldA_de => fieldA)
    })
})
.then(res=>res.json())
.then(res => console.log(res));
```


## Create / Update collection entries

#### Endpoint

```sh
/api/collections/save/{collectionname}
```

#### Example

```javascript
fetch('/api/collections/save/posts?token=xxtokenxx', {
    method: 'post',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        data: {...}
    })
})
.then(res=>res.json())
.then(entry => console.log(entry));
```


## Delete collection entries

#### Endpoint

```sh
/api/collections/remove/{collectionname}
```

#### Example

```javascript
fetch('/api/collections/remove/posts?token=xxtokenxx', {
    method: 'post',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        filter: {...}
    })
})
```
