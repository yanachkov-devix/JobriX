<?php
require_once( dirname( __FILE__ ) . '/includes/required-includes.php' ); //Necessary file containing: The necessary custom functions, Config file constants, DB connection

//Checks if a number is passed as a parameter in the _get request and if the given ID exists in the database
$jobs_id = "";
if ( ! isset( $_GET['id'] ) || ! is_numeric( $_GET['id'] ) ) {
	header( 'Location: ' . BASE_URL );
}

$jobs_id = mysqli_real_escape_string( open_db_conn(), $_GET['id'] );
$sql     = "SELECT jobs.*, user.company_name, user.company_site FROM jobs JOIN user ON user.id_user = jobs.id_user WHERE id_jobs = '$jobs_id'";
$result  = db_sql_run( $sql );

//redirect to the home page if there is no job matching such id in the database
if ( mysqli_num_rows( $result ) < 1 ) {
	header( 'Location: ' . BASE_URL );
}

$row = mysqli_fetch_assoc( $result );


$job = array(
	'title'               => '',
	'company_name'        => '',
	'company_description' => '',
	'company_site'        => '',
	'publication_date'    => '',
	'jobs_location'       => '',
	'salary'              => '',
	'description'         => '',
	'id_user'             => '',
);

foreach ( $row as $key => $value ) {
	if ( ! empty( $value ) && array_key_exists( $key, $job ) ) {
		$job[ $key ] = $value;
	}
}


//params for include template
$meta_title = $job['title'] . " | Jobrix.tk";

//header template include
require_once( dirname( __FILE__ ) . '/includes/theme-compat/header.php' ); ?>
<section class="section-fullwidth">
    <div class="row">
        <div class="job-single">
            <div class="job-main">
                <div class="job-card">
                    <div class="job-primary">
                        <header class="job-header">
                            <h2 class="job-title"><?php echo $job['title'] ?></h2>
                            <div class="job-meta">
								<?php
								if ( ! empty( $job['company_site'] ) ) { ?>
                                    <a class="meta-company" href="
                                       <?php echo 'http://' . $job['company_site'] ?>"><?php echo $job['company_name'] ?>
                                    </a>
								<?php } else {
									echo $job['company_name'];
								} ?>
                                <span class="meta-date">
                                    <?php echo date_difference( $job['publication_date'] ); ?>
                                </span>
                            </div>
                            <div class="job-details">
                                <span class="job-location">
                                    <?php if ( ! empty( $job['jobs_location'] ) ) {
	                                    echo "Job location: " . $job['jobs_location'];
                                    } else {
	                                    echo "No location specified";
                                    } ?>
                                </span>
                                <span class="job-type">Contract staff:</span>
                                <span class="job-price"><?php echo $row['salary']; ?> лв.</span>
                            </div>
                        </header>
                        <div class="job-body">
							<?php echo $job['description']; ?>
                        </div>
                    </div>
                </div>
            </div>
            <aside class="job-secondary">
                <div class="job-logo">
                    <div class="job-logo-box">
						<?php $img_file_logo = "./img/company/" . $job['id_user'] . ".jpg";
						if ( file_exists( $img_file_logo ) ) {
							echo '<img src="' . BASE_URL . "/img/company/" . $job['id_user'] . ".jpg" . '" alt="company logo">';
						} else {
							echo '<img src="' . BASE_URL . "/img/company/" . "404.gif" . '" alt="company logo">';
						} ?>
                    </div>
                </div>
                <a href="#" class="button button-wide">Apply now</a>
				<?php if ( ! empty( $job['company_site'] ) ) { ?>
                    <a href="<?php echo "http://" . $job['company_site']; ?>"
                       target="_blank"><?php echo $job['company_site']; ?>
                    </a>
				<?php } ?>
            </aside>
        </div>
    </div>
</section>
<?php require_once( $_SERVER['DOCUMENT_ROOT'] . '/includes/theme-compat/footer.php' ); ?>
