

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="robots" content="noindex" />
  <style>
    html, body {
      height: 100%;
      margin: 0;
      overflow: hidden;
      width: 100%;
    }
  </style>
  <link href="../core/GraphQL/assets/graphiql.css" rel="stylesheet" />
  <script src="../core/GraphQL/assets/react.min.js"></script>
  <script src="../core/GraphQL/assets/react-dom.min.js"></script>
  <script src="../core/GraphQL/assets/graphiql.min.js"></script>
  
  <script src="../core/GraphQL/assets/fetch.min.js"></script>
  
</head>
<body>
  <script>
    // Collect the URL parameters
    var parameters = {};
    window.location.search.substr(1).split('&').forEach(function (entry) {
      var eq = entry.indexOf('=');
      if (eq >= 0) {
        parameters[decodeURIComponent(entry.slice(0, eq))] =
          decodeURIComponent(entry.slice(eq + 1));
      }
    });
    // Produce a Location query string from a parameter object.
    function locationQuery(params, location) {
      return (location ? location: '') + '?' + Object.keys(params).map(function (key) {
        return encodeURIComponent(key) + '=' +
          encodeURIComponent(params[key]);
      }).join('&');
    }
    // Derive a fetch URL from the current URL, sans the GraphQL parameters.
    var graphqlParamNames = {
      query: true,
      variables: true,
      operationName: true
    };

      // Defines a GraphQL fetcher using the fetch API.
      function graphQLHttpFetcher(graphQLParams, options) {
        console.log((arguments));
          return fetch(window.location.origin + '@route('/graphql/query')', {
            method: 'post',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json',
              ...(options && options.headers && typeof options.headers == "object" && Array.isArray(options.headers) ? options.headers : {})

            },
            body: JSON.stringify(graphQLParams)
          }).then(function (response) {
            return response.text();
          }).then(function (responseBody) {
            try {
              return JSON.parse(responseBody);
            } catch (error) {
              return responseBody;
            }
          });
      }
    
      var fetcher = graphQLHttpFetcher;

    // When the query and variables string is edited, update the URL bar so
    // that it can be easily shared.
    function onEditQuery(newQuery) {
      parameters.query = newQuery;
      
    }
    function onEditVariables(newVariables) {
      parameters.variables = newVariables;
      
    }
    function onEditOperationName(newOperationName) {
      parameters.operationName = newOperationName;
      
    }
    function updateURL() {
      var cleanParams = Object.keys(parameters).filter(function(v) {
        return parameters[v];
      }).reduce(function(old, v) {
        old[v] = parameters[v];
        return old;
      }, {});
      history.replaceState(null, null, locationQuery(cleanParams) + window.location.hash);
    }
    // Render <GraphiQL /> into the body.
    ReactDOM.render(
      React.createElement(GraphiQL, {
        fetcher: fetcher,
        // onEditQuery: onEditQuery,
        // onEditVariables: onEditVariables,
        onEditOperationName: onEditOperationName,
        // query: "{}",
        // response: null,
        // variables: null,
        operationName: "Query",
        // editorTheme: null,
        // websocketConnectionParams: null,
      }),
      document.body
    );
  </script>
  <style>

    button.graphiql-un-styled.graphiql-tab-add {
      background: #fff;
      margin: 0 10px;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: var(--border-radius-8);
    }
    .graphiql-tabs {
      padding: 0 var(--px-12);
    }
    .graphiql-tabs, .graphiql-tab.graphiql-tab {
        height: 40px;
    }
    .graphiql-logo
    /* , button.graphiql-un-styled.graphiql-tab-add */
    , .graphiql-editor-tools-tabs > button:nth-child(2) {
      display: none;
    }
    /* .graphiql-container .editorWrap{
      overflow: hidden;
    }
    body > div > div.editorWrap > div.topBarWrap > div > div.title{
      display: none;
    }
    body > div > div.editorWrap > div.topBarWrap > div > div.execute-button-wrap{
      margin-left: 0;
    } */
  </style>

</body>
</html>