function show_details($ID) {
	var x = document.getElementById($ID);
	if (x.style.display === "none") {
		x.style.display = "table-cell";
	}
	else {
		x.style.display = "none";
	}
}