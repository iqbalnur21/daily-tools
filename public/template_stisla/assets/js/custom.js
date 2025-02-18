/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

var path = location.pathname.split("/");
var url = location.origin + "/" + path[1] + "/" + path[2];
var url1 = location.origin + "/" + path[1];

$("ul.sidebar-menu li a").each(function () {
  var href = $(this).attr("href");
  if (href.indexOf(url) !== -1) {
    $(this)
      .parent()
      .addClass("active")
      .parent()
      .parent("li")
      .addClass("active");
    return false;
  }
});

$(document).ready(function () {
  $("#datatables").DataTable({});
});

document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".currency-format").forEach((input) => {
    input.addEventListener("input", function () {
      formatCurrency(this);
    });

    // Format initially if there's already a value
    if (input.value) {
      formatCurrency(input);
    }
  });

  function formatCurrency(input) {
    let value = input.value.replace(/\D/g, ""); // Remove non-numeric characters
    if (value === "") {
      input.value = "";
      return;
    }

    let formattedValue = new Intl.NumberFormat("id-ID").format(value);
    input.value = `Rp ${formattedValue}`;
  }
});

function submit(id) {
  $("#del-" + id).submit();
}
function Logout() {
  var link = $("#Logout").attr("href");
  $(location).attr("href", link);
}

// Get all input and select elements
const inputs = document.querySelectorAll("input.length");
const selects = document.querySelectorAll("select");
// console.log("inputs:", inputs);
// console.log("selects:", selects);

// Function to update input values based on select margin-left
function syncInputsToSelects() {
  selects.forEach((select, index) => {
    const marginLeft = parseFloat(window.getComputedStyle(select).marginLeft);
    // console.log("marginLeft:", marginLeft);
    if (inputs[index]) {
      inputs[index].value = marginLeft;
    }
  });
}

// Function to update select margin-left based on input values
function syncSelectsToInputs() {
  inputs.forEach((input, index) => {
    if (selects[index]) {
      selects[index].style.marginLeft = `${input.value}px`;
      inputs[index].style.marginLeft = `${input.value}px`;
    }
  });
}

// Initial synchronization
syncInputsToSelects();

// Add event listeners to keep elements in sync
inputs.forEach((input, index) => {
  input.addEventListener("input", () => {
    if (selects[index]) {
      selects[index].style.marginLeft = `${input.value}px`;
      inputs[index].style.marginLeft = `${input.value}px`;
    }
  });
});

selects.forEach((select) => {
  select.addEventListener("change", syncInputsToSelects);
});

document.querySelectorAll(".add-element").forEach((button) => {
  button.addEventListener("click", function () {
    // Find the input container (div with margin-bottom: 5px) that is a sibling of the button
    const inputContainer = this.parentElement.previousElementSibling;

    // Create new input element
    const newInput = document.createElement("input");
    newInput.type = "text";
    newInput.className = "length";
    newInput.value = "0";
    newInput.style.marginLeft = "0px";

    // Append the new input inside the input container
    inputContainer.appendChild(newInput);

    // Create new select element
    const newSelect = document.createElement("select");
    newSelect.style.marginLeft = "0px";

    // Get the options from the first select element in the container
    const existingOptions = this.parentElement.querySelectorAll(
      "select:first-of-type option"
    );
    existingOptions.forEach((option) => {
      const newOption = document.createElement("option");
      newOption.value = option.value;
      newOption.textContent = option.textContent;
      newOption.selected = option.selected;
      newSelect.appendChild(newOption);
    });

    // Append the new select to the current div containing the button
    this.parentElement.insertBefore(newSelect, this);

    // Add event listeners to the new input and select elements
    newInput.addEventListener("input", () => {
      const marginValue = `${newInput.value}px`;
      newSelect.style.marginLeft = marginValue;
      newInput.style.marginLeft = marginValue;
    });

    newSelect.addEventListener("change", () => {
      const marginLeft = parseFloat(
        window.getComputedStyle(newSelect).marginLeft
      );
      newInput.value = marginLeft;
    });
  });
});
