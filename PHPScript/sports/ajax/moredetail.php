<?php
include "../config.inc.php";
$querystring=$_POST['querystring'];


$coach_user = mysqli_query($conn,"SELECT * FROM tbl_trainer_profile WHERE id = '$querystring'");
$coach = mysqli_fetch_array($coach_user);

$user_details = mysqli_query($conn,"SELECT * FROM tbl_user WHERE id = '".$coach['user_id']."'");
$details = mysqli_fetch_array($user_details);

$sports_details = mysqli_query($conn,"SELECT * FROM tbl_category WHERE id = '".$coach['sport_id']."'");
$category = mysqli_fetch_array($sports_details);

?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel1">Coach Details</h3>
    </div>
    <table class="table table-striped">
        <tbody>
        <tr>
        <th>Name :</th>
        <td><?php echo $details['user_name']; ?></td>
        </tr>
        <tr>
            <th>Email :</th>
            <td><?php echo $details['email'];?></td>
        </tr>
        <tr>
            <th>Status :</th>
            <td>
            <?php
            $setStatus = "<span style='color:#808000;font-weight:bold'>Pending</span>";
            if ($details['status'] == 1) {
                $setStatus = "<span style='color:#00f;font-weight:bold'>Active</span>";
            } else if ($details['status'] == 2) {
                $setStatus = "<span style='color:#f00;font-weight:bold'>Deactive</span>";
            }
            echo $setStatus;
            ?>
            </td>
        </tr>
        <tr>
            <th style="width: 200px;">Gender :</th>
            <td><?php echo $details['gender']; ?></td>
        </tr>
        <tr>
            <th style="width: 200px;">Age :</th>
            <td><?php echo $coach['age']; ?></td>
        </tr>
        <tr>
            <th style="width: 200px;">Training Level :</th>
            <td><?php echo $coach['level']; ?></td>
        </tr>
        <tr>
            <th style="width: 200px;">Section :</th>
            <td><?php echo $category['category_name']; ?></td>
        </tr>
        <tr>
            <th style="width: 200px;">Experience :</th>
            <td><?php echo $coach['experience']; ?></td>
        </tr>
        <tr>
            <th style="width: 200px;">Qualification :</th>
            <td><?php echo $coach['qualification']; ?></td>
        </tr>
        <tr>
            <th style="width: 200px;">About Coach :</th>
            <td><?php echo $coach['about_coach']; ?></td>
        </tr>
        <tr>
            <th style="width: 200px;">Achievement :</th>
            <td><?php echo $coach['achievement']; ?></td>
        </tr>
        <tr>
            <th style="width: 200px;">License :</th>
            <td><?php echo $coach['license']; ?></td>
        </tr>
        <tr>
            <th style="width: 200px;">Joining Date :</th>
            <td><?php echo $coach['joining_date']; ?></td>
        </tr>
        <tr>
            <th style="width: 200px;">College :</th>
            <td><?php echo $coach['college']; ?></td>
        </tr>
        <tr>
            <th style="width: 200px;">Address :</th>
            <td><?php echo $coach['location']; ?></td>
        </tr>
        <tr>
            <th style="width: 200px;">City :</th>
            <td><?php echo $coach['city']; ?></td>
        </tr>
        <tr>
            <th style="width: 200px;">Country :</th>
            <td><?php echo $coach['country']; ?></td>
        </tr>
        </tbody>
    </table>