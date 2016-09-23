<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('header'); ?>

	<div class="container">
		<section>
		
			<table id="ejemplo_mio" class="display" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>ID</th>
						<th>First name</th>
						<th>Last name</th>
						<th>ZIP / Post code</th>
						<th>Country</th>
					</tr>
				</thead>
			</table>

			
	</div>




<script type="text/javascript" language="javascript">


$(document).ready(function() {
	$('#ejemplo_mio').DataTable( {
		serverSide: true,
		ordering: false,
		searching: false,
		ajax: function ( data, callback, settings ) {
			var out = [];

			for ( var i=data.start, ien=data.start+data.length ; i<ien ; i++ ) {
				out.push( [ i+'-1', i+'-2', i+'-3', i+'-4', i+'-5' ] );
			}

			setTimeout( function () {
				callback( {
					draw: data.draw,
					data: out,
					recordsTotal: 5000000,
					recordsFiltered: 5000000
				} );
			}, 50 );
		},
		dom: "rtiS",
		scrollY: 200,
		scroller: {
			loadingIndicator: true
		}
	} );
} );


	</script>

<?php $this->load->view( 'footer' ); ?>