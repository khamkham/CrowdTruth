@extends('layouts.default')

@section('content')

<div class="col-xs-10 col-md-offset-1">
	<div class='maincolumn CW_box_style'>

		<div class='tab'>
			@include('process.nav')
			@include('layouts.flashdata')
			<div>
				<div class="panel panel-default">		
					<div class="panel-heading">
						<h4>Choose platform-specific options</h4>
					</div>
					<div class="panel-body">
						{{ Form::model($crowdtask, array('class' => 'form-horizontal crowdtask', 'action' => array('ProcessController@postFormPart', 'submit'), 'method' => 'POST'))}}
						<div data-toggle="buttons">
							<label>Select the platform you want to send your job to:</label>	
						  <label class="btn btn-primary active">
						    {{ Form::checkbox('amt', 'false', true, array('id' => 'amt-button') )}} Mechanical Turk
						  </label>
						  <label class="btn btn-primary">
						   	{{ Form::checkbox('cf', 'false')}} Crowdflower
						  </label>
						</div>
						<div id="amt-div" style="padding: 10px;">
							<fieldset>
								<legend>Duration</legend>
									{{ Form::label('lifetimeInSeconds', 'HIT Lifetime (seconds)', 
										array('class' => 'col-xs-4 control-label')) }}
									<div class="input-group col-xs-2">
										{{ Form::input('number','lifetimeInSeconds',  null, 
											array('class' => 'form-control input-sm', 'min' => '1')) }}
									</div>
									<br>
									{{ Form::label('autoApprovaldelayInSeconds', 'Auto approval delay (seconds)', 
										array('class' => 'col-xs-4 control-label')) }}
									<div class="input-group col-xs-2">
									{{ Form::input('number','autoApprovalDelayInSeconds',  null, 
										array('class' => 'form-control input-sm', 'placeholder' => '1 day = 86400', 'min' => '1')) }}
									</div>
								</fieldset>
								<br>
								<br>
							<fieldset>
							<legend>Misc.</legend>
								{{ Form::label('requesterAnnotation', 'Requester Annotation', 
									array('class' => 'col-xs-4 control-label')) }}
								<div class="input-group col-xs-2">
									{{ Form::text('requesterAnnotation',  null, 
										array('class' => 'form-control input-sm')) }}
								</div>
							</fieldset>
							<br>
							<br>
							<fieldset>
								<legend>Qualification Requirements</legend>
								<?php	$types = array(	'000000000000000000L0' => 'Assignments Approved (%)',
														'00000000000000000040' => 'Number of HITs Approved',
														'00000000000000000071' => 'Locale',
														'00000000000000000060' => 'Adult'		
												); ?>

								<div class="form-group">


								@foreach($types as $key=>$val)	
								<?php 
									// standard values.
									$c = false; $o = 'EqualTo'; $t = '';
									if($val=='Locale' || $val == 'Adult') $o = 'EqualTo';
									if($val=='Adult') $t = '1';
									if($crowdtask->qualificationRequirement){
											foreach($crowdtask->qualificationRequirement as $q){
												if($q['QualificationTypeId'] == $key){
													$c = true;
													if(isset($q['Comparator'])) 		$o = $q['Comparator'];
													if(isset($q['IntegerValue']))		$t = $q['IntegerValue'];
													if(isset($q['LocaleValue'])) 		$t = $q['LocaleValue'];
													if(isset($q['RequiredToPreview']))	$x = $q['RequiredToPreview'];

												} 
											}
										}
									?>

									<div class="col-sm-4">
									{{ Form::checkbox("qr[$key][checked]", 'true', $c, array('id' => $key)) }}
									{{ Form::label($key, $val) }}

									</div>
									<div class="input-group col-sm-4">	
									{{ Form::select("qr[$key][comparator]", array(
										'LessThan' => '<',
										'LessThanOrEqualTo' => '<=',
										'GreaterThan' => '>',
										'GreaterThanOrEqualTo' => '>=',
										'EqualTo' => '==',
										'NotEqualTo' => '!=',
										'Exists' => '&exist;'
									), $o, array('style' => 'margin-right:10px; line-height:29px; height:29px')) }}

									{{ Form::text("qr[$key][value]", $t, array('class' => 'form-control input-sm', 'style' => 'width:100px')) }}
									</div>		
								@endforeach				
								</div>
							</fieldset>
							<fieldset>
								<legend>Assignment Review Policy</legend>
							
							<label>AnswerKey</label><br>

							<?php
									 $arp = $crowdtask->assignmentReviewPolicy;
									 foreach($questionids as $qid){
									 	$val = '';		
									 	if($arp)
									 		foreach($arp['AnswerKey'] as $q=>$v)
									 			if($q == $qid) $val = $v;

										echo "<label class='col-xs-4'>$qid</label><div class='input-group col-xs-4'><input name='answerkey[$qid]' value='$val' class='form-control input-sm'/></div>";
									 }
								?>
							<br>
							<br>
							<label>Parameters</label><br>
							<?php
									$types = array( 'ApproveIfKnownAnswerScoreIsAtLeast', 'ApproveReason', 'RejectIfKnownAnswerScoreIsLessThan', 
													'RejectReason', 'ExtendIfKnownAnswerScoreIsLessThan', 
													'ExtendMaximumAssignments', 'ExtendMinimumTimeInSeconds'); ?>
							@foreach($types as $type)
								@if(isset($arp['Parameters'][$type]))
									<?php $c = true; $val = $v; ?>
								@else 
									<?php $c = false; $val = ''; ?>		
								@endif 
										<span class='col-sm-5'>
											{{ Form::checkbox("arp[$type][checked]", 'true', $c, array('id' => $type)) }}
											{{ Form::label($type, $type) }}
										</span>
											{{ Form::text("arp[$type][0]", $val, array('class' => 'col-sm-4')) }}
										<br><br>
							@endforeach
							</div>
							</fieldset>
							<br>
							<br>
						{{ Form::submit('Next', array('class' => 'btn btn-lg btn-primary pull-right')); }}
						{{ Form::close()}}					
					</div>
				</div>
			</div>	
		</div>
	</div>
</div>		

@endsection

@stop