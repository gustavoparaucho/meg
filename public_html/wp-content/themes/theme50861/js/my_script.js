$(document).ready(function(){
	jQuery("ul.list_1 li:nth-child(1)").addClass("item_1");
	jQuery("ul.list_1 li:nth-child(2)").addClass("item_2");
	jQuery("ul.list_1 li:nth-child(3)").addClass("item_3");
	jQuery("ul.list_1 li:nth-child(4)").addClass("item_4");

	jQuery(".list_2 ul li:nth-child(3n)").addClass("item_1");
	jQuery(".list_2 ul li:nth-child(3n-2)").addClass("item_2");
});