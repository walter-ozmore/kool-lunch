
class CustomInput {
	constructor(eleArgs={}) {
		this.inputEle = undefined;
		this.labelEle = $("<label>");
		this.div = $("<div class='form-line'>");

		if("parentEle" in eleArgs) {
			let parentEle = eleArgs["parentEle"];
			this.setParent( parentEle );
		}

		if("title" in eleArgs) {
			let title = eleArgs["title"];
			let titleEle = $("<label class='input-title'>").text(title);
			this.div.append(titleEle); // Add the title to our div
		}
	}

	/**
	 * Collects and returns the info in the input
	 */
	collect() {
		if(this.inputEle == undefined)
			return undefined;

		return this.inputEle.val()
	}

	setLabel(text) {
		this.labelEle.text(text);
	}

	setParent(parentEle) {
		parentEle.append( this.div );
	}
}

class CustomKeyInput extends CustomInput {
	/**
	 * Char limit (Length)
	 * Char limit (What type of char can be entered)
	 */
	constructor(eleArgs={}) {
		super(eleArgs);

		let inputEle = $("<input type='text'>");
		this.div.append(inputEle); // Add the title to our div

		// this.selected = []; // A list of selected jquery element
		// this.maxOptions = ("maxOptions" in eleArgs)? eleArgs["maxOptions"] : 1; // Max selected options
	}
}

class CustomMultiSelect extends CustomInput {
	/**
	 * This can be multiple things, radio menu primarly or multiple checkboxes dropdown
	 */
	constructor(eleArgs={}) {
		super(eleArgs);

		this.selected = []; // A list of selected jquery element
		this.maxOptions = ("maxOptions" in eleArgs)? eleArgs["maxOptions"] : 1; // Max selected options

		if("options" in eleArgs) {
			this.addOptions(eleArgs["options"]);
		}
	}

	addOption(optionInfo={}) {
		// Figure out the text
		let text = (optionInfo["desc"] != undefined)? optionInfo["desc"] : optionInfo["value"];

		let checkboxEle = $("<input type='checkbox'>");

		// Add click function
		checkboxEle.click(() => {
			this.manageInput(checkboxEle);

			if(checkboxEle.prop('checked'))
				if("onSelect" in optionInfo) optionInfo["onSelect"]();
			else
				if("onUnselect" in optionInfo) optionInfo["onUnselect"]();
			
			
		});

		this.div.append(
			checkboxEle,
			$("<label>").text(text),
			$("<br>")
		);

		return this;
	}

	addOptions(options) {
		if(Array.isArray(options)) {
			for(let option of options)
				this.addOption(option);
			return;
		}
	}

	/**
	 * Updates the state of the checkbox
	 * when you pass undefined the checkbox will be flipped
	 * 
	 * @param newStatus boolean True False undefined. 
	 */
	setSelected(checkboxEle, newStatus, args) {
		if(newStatus == undefined)
			newStatus = !checkboxEle.prop('checked');

		checkboxEle.prop('checked', newStatus); // Update the checkbox

		if(checkboxEle.prop('checked'))
			if("onSelect" in args) args["onSelect"]();
		else
			if("onUnselect" in args) args["onUnselect"]();
	}

	manageInput(checkboxEle, args) {
		// Remove 
		if(checkboxEle.prop('checked') == false) {
			// Remove this element from our selected array
			let index = this.selected.indexOf(checkboxEle);  // Find the index of the first occurrence of 2
			if (index !== -1)  // Check if the element exists in the array
				this.selected.splice(index, 1);  // Remove 1 element at the found index
			return;
		}

		
		// Remove the first element from the array
		if(this.maxOptions > 0) {
			while(this.selected.length >= this.maxOptions) {
				let ele = this.selected[0];
				this.selected.shift(); // Remove the first element of the array
				ele.prop('checked', false);
			}
		}
		
		this.selected.push(checkboxEle); // Puts the element on the end
	}
}
