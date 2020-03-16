$( document ).ready(function() {
  console.log( "ready!" );
  var positiveCards = [1, 2, 3, 4, 5, 6];
  var negativeCards = [-1, -2, -3, -4, -5, -6]
  for (let index = 0; index < positiveCards.length; index++) {
    console.log(positiveCards[index]);
    console.log(negativeCards[index]);
    $('.cardsRow1').append("<img class='pazaakCard' src=images/cards/plus"+positiveCards[index]+".jpg height='120px' width='90px' />");
    $('.cardsRow2').append("<img class='pazaakCard' src=images/cards/minus"+negativeCards[index]+".jpg height='120px' width='90px' />");
  }




});


const pazaakCard = document.querySelector('.pazaakCard');
const emptyCardSlot = document.querySelectorAll('.emptyCardSlot');

// Fill listeners
pazaakCard.addEventListener('dragstart', dragStart);
pazaakCard.addEventListener('dragend', dragEnd);


;



// Loop through empty boxes and add listeners
for (const emptyCardSlot of emptyCardSlots) {
  emptyCardSlot.addEventListener('dragover', dragOver);
  emptyCardSlot.addEventListener('dragenter', dragEnter);
  emptyCardSlot.addEventListener('dragleave', dragLeave);
  emptyCardSlot.addEventListener('drop', dragDrop);
}


// Drag Functions
function dragStart() {
  this.className += ' slotWhileHeld';
  //Stops item from being fully invisible until you drag it where you want it.
  setTimeout(() => (this.className = 'invisible'), 0);
  console.log('Dragging has started');
}

function dragEnd() {
  this.className = 'pazaakCard';
  console.log('Dragging Ended');
}

function dragOver(e) {
  e.preventDefault();
  console.log('Dragging Over');
}

function dragEnter(e) {
  e.preventDefault();
  this.className += ' emptyCardSlot';
  console.log('Dragging Entered');
}

function dragLeave() {
  this.className = 'emptyCardSlot';
  console.log('Dragging Leave');
}

function dragDrop() {
  this.className = 'emptyCardSlot';
  this.append(notecard);
  console.log('Dragging Dropped');
}

