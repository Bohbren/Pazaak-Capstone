<!DOCTYPE html>
<html lang="en">
  <title>Trello-Like</title>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
    <div class="board-layout">
   
        <div class="left">
          <div class="board-text">Today Board</div>
         
        </div>
       
      <div id='boardlists' class="board-lists">
        <div id='list1' class="board-list" ondrop="dropIt(event)" ondragover="allowDrop(event)">
          <div class="list-title">
            To Do
          </div>
          
            <div  id='card1' class="card" draggable="true" ondragstart="dragStart(event)">
            Work on article
            </div>
            <div  id='card2' class="card" draggable="true" ondragstart="dragStart(event)">
            Back up database
            </div>
            <div id='card3' class="card" draggable="true" ondragstart="dragStart(event)">
            Build Lambda function
            </div>
            <div id='card4' class="card" draggable="true" ondragstart="dragStart(event)">
            Work on course content
            </div>
            <div id='card5' class="card" draggable="true" ondragstart="dragStart(event)">
            Debug SQL code
            </div>
         
        </div>
        <div  id='list2' class="board-list" ondrop="dropIt(event)" ondragover="allowDrop(event)">
          <div  class="list-title">
          In Progress
          </div>
          
          
        </div>
        <div  id='list3' class="board-list"  ondrop="dropIt(event)" ondragover="allowDrop(event)">
          <div  class="list-title">
            Done
            </div>
             
            </div>
      </div>
    </div><script src='js/deckBuilder.js'></script></body>
</html>