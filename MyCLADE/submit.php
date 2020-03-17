<?php include("./includes/header.php"); ?>

	<section id='Submission'>
	<h2> Your job is running... </h2>
    <h3>This server is a multi-source domain annotation for quantitative <em>metagenomic and metatranscriptomic</em> functional profiling.</h3>
		<p class = 'text'>
		<?php 
		/*if($_GET['form']=='large'){
				$sequences = $_POST['sequences'];
				file_put_contents('./fasta_file/fasta_tmp.fa', $sequences);

				//Picking up of parameters informations
				$dama = $_SESSION['dama'];
				if($dama){
					$DAMA_evalue = $_SESSION['DAMA-evalue'];}
				$e_value = $_SESSION['evalue'];

				MetaCLADE program
				if($dama){./metaclade2/metaclade2 -i ./fasta_file/fasta_temp.fa -N results -d $pfam -W ../ -j 2}
				else{./metaclade2/metaclade2 -i ./fasta_file/fasta_temp.fa -a -N results -d $pfam -W ../ -j 2}

		}
		else if($_GET['small']=='large'){
				$sequences = $_POST['sequences'];
				file_put_contents('./fasta_file/fasta_tmp.fa', $sequences);
				$pfam = $_POST['pfam_domains'];
				$dama = $_SESSION['dama'];
				if($dama){
					$DAMA_evalue = $_SESSION['DAMA-evalue'];}
				$e_value = $_SESSION['evalue'];
				
				MetaCLADE program
				if($dama){./metaclade2/metaclade2 -i ./fasta_file/fasta_temp.fa -N results -d $pfam -W ../ -j 2}
				else{./metaclade2/metaclade2 -i ./fasta_file/fasta_temp.fa -a -N results -d $pfam -W ../ -j 2}

		} */
		?>
		Biochemical and regulatory pathways have until recently been thought and modelled within one cell type, one organism, one species. This vision is being dramatically changed by the advent of whole microbiome sequencing studies, revealing the role of symbiotic microbial populations in fundamental biochemical functions. The new landscape we face requires the reconstruction of biochemical and regulatory pathways at the community level in a given environment. In order to understand how environmental factors affect the genetic material and the dynamics of the expression from one environment to another, one wishes to quantitatively relate genetic information with these factors. For this, we need to be as precise as possible in evaluating the quantity of gene protein sequences or transcripts associated to a given pathway. We wish to estimate the precise abundance of protein domains, but also recognise their weak presence or absence.
		</p>
		<p>
		We introduce MetaCLADE2, and improved profile-based domain annotation pipeline based on the multi-source domain annotation strategy. It provides a domain annotation realised directly from reads, and reaches an improved identification of the catalog of functions in a microbiome. MetaCLADE2 can be applied to either metagenomic or metatranscriptomic datasets as well as proteomes.
		</p>
	</section>


<?php include("./includes/footer.php"); ?>