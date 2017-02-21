$(document).ready(function() {
// Initializing arrays with city names.
var Computer= [{
display: "Bachelor of Science in Information Technology",value: "BSIT"},
{display: "Bachelor of Science in Computer Science", value: "BSCS"},
{display: "Bachelor of Science in Information Systems", value: "BSIS"}];
var Engineering = [{
display: "Bachelor of Science in Computer Engineering",value: "BSCoE"},
{display: "Bachelor of Science in  Electronics Engineering",value: "BSEE"},
{display: "Bachelor of Science in Industrial Engineering",value: "BSIE"},
{display: "Bachelor of Science in Electrical Engineering",value: "BSEE"}];
var Business = [{
display: "Bachelor of Science in Accountancy",value: "BSA"},
{display: "Bachelor of Science in Business Administration",value: "BSBA"}];
var Arts = [{
display: "Bachelor of Arts in Mass Communication", value: "BAMC"},
{display: "Bachelor of Arts in English", value: "BAE"},
{display: "Bachelor of Arts in Political Science", value: "BAPolsci"},
{display: "Bachelor of Arts in Psychology", value: "BAPsych"},
{display: "Bachelor of Arts in Economics", value: "BAEco"}];
var Education = [{
display: "Bachelor of Science in Elementary Education", value: "BSEE"},
{display: "Bachelor of Science in Secondary Education", value: "BSEd"}];
// Function executes on change of first select option field.
$("#program").change(function() {
var select = $("#program option:selected").val();
//alert(select)
switch (select) {
case "Computer":
course(Computer);
break;
case "Engineering":
course(Engineering);
break;
case "Business":
course(Business);
break;
case "Arts":
course(Arts);
break;
case "Education":
course(Education);
break;
default:
$("#course").empty();
$("#course").append("<option>--Select--</option>");
break;
}
});
// Function To List out Courses in Second Select tags
function course(arr) {
    $("#course").empty(); //To reset courses
$("#course").append("<option> Select course</option>");
$(arr).each(function(i) { //to list course
$("#course").append("<option  value=\"" + arr[i].value + "\">" + arr[i].display + "</option>")
});
}
});
