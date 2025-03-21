
class CustomInput {
	constructor(eleArgs={}) {
		this.name = eleArgs["name"];
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

		this.inputEle = $("<input type='text'>");
		this.div.append(this.inputEle); // Add the title to our div

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
		this.selectedInfo = []; // A list of selected optionInfo
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
		checkboxEle.click(() => { this.manageInput(checkboxEle, optionInfo); });

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
	 * @param newStatus 
	 *  - 0: Uncheck
	 *  - 1: Check
	 *  - 2: Flip Check
	 *  - 3: No change
	 */
	setSelected(checkboxEle, status, optionInfo) {
		// Update the checkbox
		switch(status) {
			case "uncheck": 
				checkboxEle.prop('checked', false);
				break;
			
			case "check": 
				checkboxEle.prop('checked', true);
				break;

			case "flip": 
				checkboxEle.prop('checked', !checkboxEle.prop('checked'));
				break;
		}

		// Call functions
		if(checkboxEle.prop('checked') == true  && "onSelect"   in optionInfo) {
			optionInfo["onSelect"  ](optionInfo);
		}
		if(checkboxEle.prop('checked') == false && "onUnselect" in optionInfo) {
			optionInfo["onUnselect"](optionInfo);
		}
	}

	manageInput(checkboxEle, optionInfo) {
		// Handle the input with the custom functions
		this.setSelected(checkboxEle, "none", optionInfo);

		// Remove the element from the checked array
		if(checkboxEle.prop('checked') == false) {
			// Remove this element from our selected array
			let index = this.selected.indexOf(checkboxEle);  // Find the index of the first occurrence of 2
			if (index !== -1) {  // Check if the element exists in the array
				this.selected.splice(index, 1);  // Remove 1 element at the found index
				this.selectedInfo.splice(index, 1);  // Remove 1 element at the found index
			}
			return;
		}

		
		// Remove the first element from the array
		if(this.maxOptions > 0) {
			while(this.selected.length >= this.maxOptions) {
				let ele = this.selected[0];
				let tmpOptionInfo = this.selectedInfo[0];

				this.setSelected(ele, "uncheck", tmpOptionInfo);

				this.selected.shift(); // Remove the first element of the array
				this.selectedInfo.shift();
			}
		}
		
		this.selected.push(checkboxEle); // Puts the element on the end
		this.selectedInfo.push(optionInfo); // Puts the element on the end
	}
}


class FormBuilder {
	constructor(eleArgs={}) {
		this.formEles = [];
		this.formDiv = undefined;

		this.formDiv = $("<div style='background-color: blue'>");

		if("parentEle" in eleArgs) {
			eleArgs["parentEle"].append(this.formDiv);
		} else {
			$("body").append(this.formDiv);
		}
	}

	addFormEle(formEle) {
		this.formEles.push(formEle);
	}

	collect() {
		let data = {};
		for(let formEle of this.formEles) {
			let key = formEle.name;
			let value = formEle.collect();
			
			data[key] = value;
		}

		return data;
	}
}