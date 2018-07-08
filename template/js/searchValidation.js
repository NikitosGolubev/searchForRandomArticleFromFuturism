/*** Variables, constants, objects, etc... ***/

// Search input
const searchField = document.getElementById('JSsearch');
const minSearchRequestLen = 3; // min search input value length
const maxSearchRequestLen = 256; // max search input value length

/*** Event listeners ***/

document.getElementById('JSsearchBtn').addEventListener('click', validateSearchField);
searchField.addEventListener('focus', removeErrorMessage);

/*** Event functions ***/

// Validation of search input value before sending request
function validateSearchField(event) {
	// Cutting useless spaces
	let searchFieldValue = trim(searchField.value);
	// Varriable that shows current state of validation
	let isValidationSucceeded = true;

	// if user sent error message as a request
	if (isError(searchField)) isValidationSucceeded = false;

	// Verifying length of search input value
	if (searchFieldValue.length < minSearchRequestLen) {
		isValidationSucceeded = false;
		printInputError(searchField, 'Minimum request length is '+ minSearchRequestLen +' characters');
	}
	if (searchFieldValue.length > maxSearchRequestLen) {
		isValidationSucceeded = false;
		printInputError(searchField, 'Maximum request length is '+ maxSearchRequestLen +' characters');
	}

	// If validation failed, so I don't send request server
	if (!isValidationSucceeded) event.preventDefault();
}

/*** Helpers ***/

/*
	If there any errors in validation of input, so this function prints error inside input and changes its
	styling by adding special class and also sets a value to error atribute that defines this input as
	"input with error"
	inputElementOrSelector - input element or selector
	errorMessage - message that should be printed inside input
	$isSelector - if inputElementOrSelector doesnt contain selector, so this param should be false
*/
function printInputError(inputElementOrSelector, errorMessage, $isSelector = false) {
	// Defining input element
	let input = inputElementOrSelector;
	if ($isSelector) input = document.querySelector(inputElementOrSelector);

	input.value = errorMessage; // Printing error message
	input.setAttribute('error', 1); // Setting error attribute to input that shows current input state
	input.classList.add('danger'); // Styling input
}

/* 
	Removing error state from input
	selectorOrEventObjOrInput - selector OR event object OR input element
	$isEvent - if its not event, this param should be false
	$isSelector - if selectorOrEventObjOrInput doesnt contain selector, so this param should be false
*/
function removeErrorMessage(selectorOrEventObjOrInput, $isEvent = true, $isSelector = false) {
	// Defining input element
	let input = selectorOrEventObjOrInput;
	if ($isSelector) input = document.querySelector(selectorOrEventObjOrInput);
	if ($isEvent) input = selectorOrEventObjOrInput.target;

	// if input in error state currently
	if (isError(input)) {
		input.classList.remove('danger'); // Reset input styling to normal one
		input.value = ''; // Removing error message value
		input.setAttribute('error', '0'); // Changing value on error attribute
	}
}

// Defines if input is in error state
/*
	inputElementOrSelector - selector or input element
	isSelector - defines if inputElementOrSelector contains selector
*/
function isError(inputElementOrSelector, $isSelector = false) {
	// Defining input element
	let input = inputElementOrSelector;
	if ($isSelector) input = document.querySelector(inputElementOrSelector); // getting input by selector

	// If its error input, so return true, otherwise - false
	if (input.getAttribute('error') === '1') return true;
	return false;
}

// Function that cut double spaces by reg exp
function trim(str) {
	return str.replace(/\s\s+/g, " ");
}