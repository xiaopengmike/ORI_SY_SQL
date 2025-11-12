<?php
include_once("common/Common.php");

echo '<div id="treediv" style="display: none;position:absolute;overflow:hidden;min-width: 250px;min-height:200px;  padding: 5px;background: #fff;color: #fff;border: 1px solid #718A9B">
		<div align="right">
			<a href="##" id="closed">
				<font color="#000">关闭&nbsp;</font>
			</a>
		</div>
		<script language="JavaScript" type="text/JavaScript">
			mydtree = new dTree(\'mydtree\',\''. COMMON_FILE_URL .'/imgmenu/\',\'no\',\'no\');
			mydtree.add(0,
					-1,
					"部门",
					"javascript:setvalue(\'0\',\'部门\')",
					"部门",
					"_self",
					false);	
			url="'. COMMON_FILE_URL .'/gethint.php?flag=1";
			var myobj;
			$.ajaxSetup({async : false});
			$.post(url,{},function(response){
				myobj=eval(\'(\'+response+\')\');
				for(var i = 0; i < myobj.length; i++){
					BM=myobj[i].NAME;
					var ID=parseInt(myobj[i].ID);
					var PARENT=parseInt(myobj[i].PARENT);
					mydtree.add(ID,PARENT,BM,"javascript:setvalue(\'"+ID+"\',\'"+BM+"\')",BM,\'_self\',false);
				}
			});
			document.write(mydtree);
		</script>
	</div>
	<script type="text/javascript">
		xOffset = 0;
		yOffset = 25;

		var toshow = "treediv";
		var target = "menu_parent_name";

		$("#" + target).click(function() {
			$("#" + toshow)
				.css("position", "absolute")
				.css("left", $("#" + target).position().left + xOffset + "px")
				.css("top", $("#" + target).position().top + yOffset + "px").show();
		});
		$("#closed").click(function() {
			$("#" + toshow).hide();
		});
		function checkIn(id) {
			var yy = 20;
			var str = "";
			var x = window.event.clientX;
			var y = window.event.clientY;
			var obj = $("#" + id)[0];
			if (x > obj.offsetLeft && x < (obj.offsetLeft + obj.clientWidth) && y > (obj.offsetTop - yy) && y < (obj.offsetTop + obj.clientHeight)) {
				return true;
			} else {
				return false;
			}
		}
		$(document).click(function() {
			var is = checkIn("treediv");
			if (!is) {
				$("#" + toshow).hide();
			}
		});

		function setvalue(id, name) {
			$("#menu_parent_name").val(name);
			$("#menu_parent").val(id);
			$("#treediv").hide();
		}
    </script>';
    
    exit;