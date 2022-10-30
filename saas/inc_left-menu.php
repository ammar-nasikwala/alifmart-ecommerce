
<div id="leftpanel">
	<div class="glossymenu">
		<?php if($_SESSION["saas_user"]==1){?>
			
			
		<a id="menu-profile" class="menuitem<?php if($_SERVER["SCRIPT_NAME"]=="/admin/manage_users.php"){ ?>-active<?php } ?>" href="manage_users.php" title="Manage Users">Manage Users</a>

		<a id="menu-address" class="menuitem<?php if($_SERVER["SCRIPT_NAME"]=="/admin/manage_rfq.php"){ ?>-active<?php } ?>" href="manage_rfq.php" title="Manage RfQ">Manage RfQ</a>
		
		<a id="menu-details" class="menuitem<?php if($_SERVER["SCRIPT_NAME"]=="/admin/manage_quotation.php"){ ?>-active<?php } ?>" href="manage_quotation.php" title="Manage Quotation">Manage Quotation</a>

		<a id="menu-address" class="menuitem<?php if($_SERVER["SCRIPT_NAME"]=="/admin/manage_po.php"){ ?>-active<?php } ?>" href="manage_po.php" title="Manage PO">Manage PO</a>
		
		<a id="menu-address" class="menuitem<?php if($_SERVER["SCRIPT_NAME"]=="/admin/view_order.php"){ ?>-active<?php } ?>" href="view_order.php" title="View Orders">View Order</a>
			
		<?php }	else{?>
			<a id="menu-address" class="menuitem<?php if($_SERVER["SCRIPT_NAME"]=="/admin/manage_rfq.php"){ ?>-active<?php } ?>" href="manage_rfq.php" title="Manage RfQ">Manage RfQ</a>
			
			<a id="menu-details" class="menuitem<?php if($_SERVER["SCRIPT_NAME"]=="/admin/manage_quotation.php"){ ?>-active<?php } ?>" href="manage_quotation.php" title="Manage Quotation">Manage Quotation</a>
			
			<a id="menu-address" class="menuitem<?php if($_SERVER["SCRIPT_NAME"]=="/admin/manage_po.php"){ ?>-active<?php } ?>" href="manage_po.php" title="Manage PO">Manage PO</a>
		
			<a id="menu-address" class="menuitem<?php if($_SERVER["SCRIPT_NAME"]=="/admin/view_order.php"){ ?>-active<?php } ?>" href="view_order.php" title="View Orders">View Order</a>
		
		<?php } //Sub Users?> 
			
	</div>
</div>