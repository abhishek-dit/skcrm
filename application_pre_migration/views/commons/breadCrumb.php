<div class="page-head">
	<!--<div class="page-head">-->
	<h3><?php echo $breadCrumbTite;?></h3>
	<ol class="breadcrumb">
    	<?php
		if(isset($breadCrumbOptions))
		{
			$bCount = count($breadCrumbOptions);
			if($bCount>0)
			{
				foreach($breadCrumbOptions as $bCrumb)
				{
					$bClass = $bCrumb['class'];
					$bLable = $bCrumb['label'];
					$bUrl = $bCrumb['url'];
					echo '<li class="'.$bClass.'">';
					if($bUrl!='')
					echo '<a href="'.$bUrl.'">'.$bLable.'</a>';
					else
					echo $bLable;
					echo '</li>';
					
				}
			}
		}
		?>
		<!--<li><a href="#">Home</a></li>
		<li class="active">Generate PO</li>-->
	</ol>
	<!--</div>-->
</div>