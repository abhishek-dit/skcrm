<?php 
switch($pageDetails)
{
	case 'Lead':
		$leadClass = 'active';
		$opportunityClass = '';
		$quoteClass = '';
		$cNoteClass = '';
		break;
	case 'Opportunity':
		$leadClass = 'complete';
		$opportunityClass = 'active';
		$quoteClass = '';
		$cNoteClass = '';
		break;
	case 'Quote':
		$leadClass = 'complete';
		$opportunityClass = 'complete';
		$quoteClass = 'active';
		$cNoteClass = '';
		break;
	case 'CNote':
		$leadClass = 'complete';
		$opportunityClass = 'complete';
		$quoteClass = 'complete';
		$cNoteClass = 'active';
		break;
}

if($leadStatus == 1)
{
	$leadURL = SITE_URL.'openLeadDetails/'.$encode_lead_id;
	$opportunityURL = '#';
	$quoteURL = '#';
	$cNoteURL = '#';
}
else if($leadStatus == 2)
{
	$leadURL = SITE_URL.'openLeadDetails/'.$encode_lead_id;
	$opportunityURL = SITE_URL.'openOpportunityDetails/'.$encode_lead_id;
	$quoteURL = '#';
	$cNoteURL = '#';
}
else if($leadStatus == 3 )
{
	$leadURL = SITE_URL.'openLeadDetails/'.$encode_lead_id;
	$opportunityURL = SITE_URL.'openOpportunityDetails/'.$encode_lead_id;
	$quoteURL = SITE_URL.'openQuoteDetails/'.$encode_lead_id;
	$cNoteURL = '#';
}

else if($leadStatus < 20 )
{
	$leadURL = SITE_URL.'openLeadDetails/'.$encode_lead_id;
	$opportunityURL = SITE_URL.'openOpportunityDetails/'.$encode_lead_id;
	$quoteURL = SITE_URL.'openQuoteDetails/'.$encode_lead_id;
	$cNoteURL = SITE_URL.'opencNoteDetails/'.$encode_lead_id;
}

else if($leadStatus > 19 )
{
	$leadURL = SITE_URL.'closedLeadDetails/'.$encode_lead_id;
	$opportunityURL = SITE_URL.'closedOpportunityDetails/'.$encode_lead_id;
	$quoteURL = SITE_URL.'closedQuoteDetails/'.$encode_lead_id;
	$cNoteURL = SITE_URL.'closedcNoteDetails/'.$encode_lead_id;
}

?>

<ul class="steps">
	<a href="<?php echo $leadURL; ?>" style="cursor:pointer;"><li class="<?php echo $leadClass; ?>">Lead Details<span class="chevron"></span></li></a>
	<?php
	if(@$checkUser && (@$lead_row['type']==2 && (@$lead_row['site_readiness_id']==NULL || @$lead_row['relationship_id']==NULL))){

	?>
	<a data-target="#mod-warning" data-toggle="modal"><li class="">Opportunities<span class="chevron"></span></li></a>
	<?php
	}
	else{
	?>
	<a href="<?php echo $opportunityURL; ?>"><li class="<?php echo $opportunityClass; ?>">Opportunities<span class="chevron"></span></li></a>
	<?php
	}
	?>
	<a href="<?php echo $quoteURL; ?>"><li class="<?php echo $quoteClass; ?>">Quote<span class="chevron"></span></li></a>
	<a href="<?php echo $cNoteURL; ?>"><li class="<?php echo $cNoteClass; ?>">Contract Note<span class="chevron"></span></li></a>
</ul>
