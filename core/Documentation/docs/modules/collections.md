# Collections

Collections is a powerful feature that comes with Maya.
With collections you can manage different types of content lists.

> If you're familiar with WordPress, you can compare collections to post types
> or as a simple Content Construction Kit (CCK) you have maybe experienced in systems like Drupal.


## Create a Collection

![Create Collection](create.png)

Define a set of fields which define your desired content model and start collecting items.

## List collection items

![List Collection Items](list.png)

## Edit a collection item

![Edit Collection Item](edit.png)

You will get an easy to use entry form to manage your content items.

## API

### /api/collections/collection/{collectionname}

Get collection schema

```javascript
fetch('/api/collections/collection/posts?token=xxtokenxx')
    .then(collection => collection.json())
    .then(collection => console.log(collection));
```

### /api/collections/get/{collectionname}

Get collection entries

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

        lang: 'de' // return normalized language fields (fieldA_de => fieldA)
    }))
    .then(res=>res.json())
    .then(res => console.log(res));
```


### /api/collections/save/{collectionname}

Create / Update collection collection entries

```javascript
fetch('/api/collections/save/posts?token=xxtokenxx', {
    method: 'post',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        data: {...}
    }))
    .then(res=>res.json())
    .then(entry => console.log(entry));
```
