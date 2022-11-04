# Forms API


## Stores the submission of a form as an entry.

#### Endpoint

```sh
/api/forms/submit/{form_name}
```
#### Example
```javascript
fetch('/api/forms/submit/mayaForm?token=xxtokenxx', {
    method: 'post',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        form: {
            field1: 'value1',
            field2: 'value2'
        }
    })
})
.then(entry => entry.json())
.then(entry => console.log(entry));
```