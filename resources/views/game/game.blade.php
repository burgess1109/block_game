
<!--
Created using JS Bin
http://jsbin.com

Copyright (c) 2016 by anonymous (http://jsbin.com/qarekehevi/8/edit)

Released under the MIT license: http://jsbin.mit-license.org
-->
<meta name="robots" content="noindex">
<head>
  <script src="https://code.jquery.com/jquery.min.js"></script>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.16/vue.js"></script> 
  <script src="https://cdn.jsdelivr.net/vue.resource/0.9.3/vue-resource.min.js"></script>
  
  <style id="jsbin-css">
    table {border-collapse:collapse;}

    td {
      border:solid black 1px;
      width:30px;
      height:30px;
      text-align:center;
    }

    .shadow {background-color:grey;}

    .start {background-color:green;}

    .end {background-color:gold;}

    .enemy {background-color:red;}
  </style>
</head>
<body>
  <div id="app"  class="row">

    <div class="col-xs-6">


      <div id="grid-inputs">
        <div class="input-group">
         <span class="input-group-addon" id="basic-addon1">number of rows</span>
         <input type="number" class="form-control"  v-model="rowQuantity">   
       </div>
       <div class="input-group">
        <span class="input-group-addon" id="basic-addon1">number of columns</span>
        <input type="number" v-model="columnQuantity"  class="form-control" >
      </div>
      <div class="input-group">
        <span class="input-group-addon" id="basic-addon1">number of enemies</span>
        <input type="number" v-model="enemyQuantity"  class="form-control" >
      </div>  
    </div>
    <input v-on:keyup.down="move('down')" v-on:keyup.up="move('up')"
    v-on:keyup.right="move('right')"
    v-on:keyup.left="move('left')">
    
    <div id="grid">
      <table>
        <tr v-for="rowIndex in + rowQuantity"> 
          <td v-for="columnIndex in + columnQuantity" v-bind:class="{                       'enemy': isEnemy(rowIndex,columnIndex), 'shadow': isShadow(rowIndex,columnIndex),  'start': isStart(rowIndex,columnIndex), 'end': isEnd(rowIndex,columnIndex)}">
            <div v-show="isCurrent(rowIndex,columnIndex)">
              O_O
            </div>
            @{{ rowIndex }},@{{ columnIndex }}
          </td>
        </tr>
      </table>
    </div>

  </div>
</div>
<script id="jsbin-javascript">
  var exampleEnemies = [
  { 'rowIndex': 0, 'columnIndex': 5},
  { 'rowIndex': 5, 'columnIndex': 11},
  { 'rowIndex': 11, 'columnIndex': 3},
  { 'rowIndex': 11, 'columnIndex': 11},
  { 'rowIndex': 4, 'columnIndex': 4},
  { 'rowIndex': 4, 'columnIndex': 10},
  { 'rowIndex': 12, 'columnIndex': 4},
  { 'rowIndex': 12, 'columnIndex': 10},
  { 'rowIndex': 11, 'columnIndex': 0},
  { 'rowIndex': 9, 'columnIndex': 12},
  { 'rowIndex': 2, 'columnIndex': 1},
  { 'rowIndex': 2, 'columnIndex': 14},
  { 'rowIndex': 6, 'columnIndex': 2},
  { 'rowIndex': 4, 'columnIndex': 13},
  ];
  var exampleEnemies2 = [
  { 'rowIndex': 2, 'columnIndex': 1},
  { 'rowIndex': 2, 'columnIndex': 3}
  ];


  new Vue({
    el: '#app',
    data: {
      'rowQuantity': 7,
      'columnQuantity': 7,
      'enemyQuantity':0,
      'enemies': exampleEnemies,
      'current': {
        'rowIndex': 0, 'columnIndex':   0
      }   
    },
    methods: {
      isEnemy: function (rowIndex,ColumnIndex)     {
        var toCheck = {
          'rowIndex': rowIndex, 'columnIndex':   ColumnIndex
        };

        for (var x = 0; x < this.enemies.length; x++)
        {
          var enemy = this.enemies[x];
          if (isSameLocation(enemy,toCheck))
          {

            return true;
          }
        }
        return false;
      },
      isStart: function(rowIndex,ColumnIndex){
        var toCheck = {
          'rowIndex': rowIndex, 'columnIndex':   ColumnIndex
        };
        var origin =   {
          'rowIndex': 0, 'columnIndex': 0
        };

        if (isSameLocation(origin,toCheck))
        {

          return true;
        }
        return false;
      },
      isEnd: function(rowIndex,ColumnIndex){

        var toCheck = {
          'rowIndex': rowIndex, 'columnIndex':   ColumnIndex
        };
        var destination =   {
          'rowIndex': this.rowQuantity-1, 'columnIndex': this.columnQuantity-1
        };

        if (isSameLocation(destination,toCheck))
        {

          return true;
        }
        return false;

      },
      isShadow: function(rowIndex,ColumnIndex){
        var toCheck = {
          'rowIndex': rowIndex, 'columnIndex':   ColumnIndex
        };

        for (var x = 0; x < this.enemies.length; x++)
        {
          var enemy = this.enemies[x];
          if (isAdjacentLocation(enemy,toCheck))
          {
            return true;
          }
        }
        return false;
      },
      isCurrent: function(rowIndex,columnIndex)
      {

        var toCheck = {
          'rowIndex': rowIndex, 'columnIndex':   columnIndex
        };
        if (isSameLocation(this.current,toCheck))
        {
          return true;
        }
        return false;    
      },
      getNewGrid: function(){


      // perform ajax request
      
      gridParameters = {
        'rowQuantity': this.rowQuantity,
        'columnQuantity': this.columnQuantity,
        'enemyQuantity': this.enemyQuantity,
        '_token': "<?php echo csrf_token();?>"
      };
      // console.log(gridParameters);
      this.$http.post('/game/grid', gridParameters)
      .then(
      // on success
      (response) => {
        console.log(response.json());

        this.enemies = response.json();
      },
      // on error 
      (response) => {

        console.log(response);
      });
      
      // change enemies location
      //this.enemies = exampleEnemies2;
    },
    move: function(direction){
      if (direction == 'up')
      {
       this.current.rowIndex --;   
     }
     if (direction == 'down')
     {
      this.current.rowIndex ++;
    }
    if (direction == 'right')
    {
      this.current.columnIndex ++;  
    }
    if (direction == 'left')
    {
      this.current.columnIndex --;  
    }

  }


},
watch: {
  'rowQuantity': 'getNewGrid',    
  'columnQuantity': 'getNewGrid',
  'enemyQuantity': 'getNewGrid'
}



});


  function isSameLocation(first,second)
  {
   if (first.rowIndex == second.rowIndex && first.columnIndex == second.columnIndex)
   {
     return true;
   }
   return false;
 }

 function isAdjacentLocation(first,second)
 {
  surroundingFirst = [
  { 
    'rowIndex': first.rowIndex+1, 
    'columnIndex': first.columnIndex
  },
  { 
    'rowIndex': first.rowIndex-1, 
    'columnIndex': first.columnIndex
  },
  { 
    'rowIndex': first.rowIndex, 
    'columnIndex': (first.columnIndex+1)
  },
  { 
    'rowIndex': first.rowIndex, 
    'columnIndex': first.columnIndex-1
  },
  ];
  
  for (var x = 0; x < surroundingFirst.length; x++)
  {
    newFirst = surroundingFirst[x];
    if (isSameLocation(newFirst,second))
    {
      return true; 
    }
  }
  
  return false;
  
}
</script>
</body>