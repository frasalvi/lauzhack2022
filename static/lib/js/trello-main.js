(function () {
	'use strict'

	//Board constructor object and assign some properties to its prototype
	function Board(title) {
		var nextId = 0

		this.title = title
		this.lists = []
		this.cards = {}

		this.node = document.createElement('div')
		this.titleNode = document.createElement('div')
		this.listsNode = document.createElement('div')

		this.node.id = 'board'
		this.titleNode.id = 'trello-title-board'
		this.listsNode.id = 'trello-canvas-board'

		// new list title form
		this.titleFormNode = buildListTitleForm()
		this.titleNode.appendChild(document.createTextNode(this.title))

		this.getNextId = function () {
			return '_' + (nextId++).toString()
		}
	}

	Board.prototype.render = function () {
		this.lists.push(new List(this, 'Add a list...', 0, true))
		for (var i = 0; i < this.lists.length; ++i) {
			this.listsNode.appendChild(this.lists[i].node)
		}
		this.lists[this.lists.length - 1].node.appendChild(this.titleFormNode)
		this.lists[this.lists.length - 1].titleNode.onclick = addListTrello(this)
		this.node.appendChild(this.titleNode)
		this.node.appendChild(this.listsNode)
	}

	Board.prototype.registerCard = function (card, index) {
		this.cards[card.id] =
		{
			card: card
			, list: card.list
			, index: index
		}
		// print in console every time the card is dragged somewhere else and print the new category
		card.node.ondragend = function (evt) {
			console.log("dragend: " + evt)
			console.log("card: " + card.title + " is now in " + card.list.title)
			// print the inner div id
			console.log("card: " + card.title + " is now in " + card.list.title + " with id " + card.issue_id)

			// Send an asynchronous post request to the server to update the card's category
			$.ajax({
				type: "POST",
				url: "/update_issue_status",
				data: {
					"issue_id": card.issue_id,
					"status": card.list.title
				},
				success: function (data) {
					// print the response from the server
					console.log(data)
					}
			});


		}


	}

	Board.prototype.reregisterSubsequent = function (list, index, shift) {
		for (var i = index; i < list.cards.length; ++i) {
			this.registerCard(list.cards[i], i + shift)
		}
	}

	Board.prototype.unregisterCard = function (card) {
		delete this.cards[card.id]
	}


	//if you click on escape then also the edit window will get closed
	window.onkeydown = function (evt) {
		if (evt.keyCode === 27) {
			cardEdit.close()
		}
	}


	//Onloading the document render the board.The code starts from here
	document.body.onload = function () {
		var title = 'Add New Board'
			, board = new Board(title)

		board.render()
		document.getElementById('container').appendChild(board.node)
		currentBoard = board

		proposalsLoad();
	}
}())
