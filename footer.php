	
	</div>
	<div class="ui middle aligned one column grid" style="background-color: #222; margin-top: 30px;">
		<div class="center aligned column" style="color: #fff;">&copy; <?php echo date("Y"); ?></div>
	</div>

	<div class="ui basic modal">
	  <i class="close icon"></i>
	  <div class="image center aligned content">
	    <img class="image" src="" style="">
	  </div>
	</div>

	<script type="text/javascript">
	    $("img.pic").click(function(){
	        $(".ui.modal img").attr("src",$(this).attr("src"));
	        $('.ui.modal').modal({blurring: true, closeIcon: true}).modal('show');
	    });

	    $("#getChatText").click(function(){
	    	$("#showChatText").fadeToggle();
	    });

	    $('.socialChats .item').tab();

		function copyTxt(elementID) {

			if(elementID == 'facebookTxt'){
				navigator.clipboard.writeText($("#facebookTxt").html());
			}else{
				var copyText = document.getElementById(elementID);
				copyText.select();
				copyText.setSelectionRange(0, 99999); // For mobile devices				
				navigator.clipboard.writeText(copyText.value);
			}

			// Copy the text inside the text field
			
		}

	</script>

</body>
</html>