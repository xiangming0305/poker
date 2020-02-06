<div class='affiliateRequests'>
<h2>Affiliate Requests</h2>
<p>Here are displayed all players' requests to become an affiliate and start earning real money instead of points!</p>

<table>
    <thead>
        <td>Player name</td>
        <td>Request datetime</td>
        <td>Actions</td>
        <td>Status</td>
        <td>Details</td>
    </thead>
    
    <tbody>
        <?php
            require_once $_SERVER['DOCUMENT_ROOT']."/classes/Classes.php";
            
            foreach(AffiliateRequests::_list() as $v){
                $user = UserMachine::getUserByPlayerName($v['user']);
                switch($v['status']){
                    case AffiliateRequests::STATUS_WAITING:{
                        $status = "<span class='stat waiting'>Waiting</span>";
                        break;
                    }
                    
                    case AffiliateRequests::STATUS_ACCEPTED:{
                        $status = "<span class='stat accepted'>Accepted</span>";
                        break;
                    }

                    case AffiliateRequests::STATUS_ACCEPTED_ONCE_ENABLE:{
                        $status = "<span class='stat accepted' title='Accepted, but user does not meet the affiliate criteria'>Accepted/Pending</span>";
                        break;
                    }


                    case AffiliateRequests::STATUS_DECLINED:{
                        $status = "<span class='stat declined'>Declined</span>";
                        break;
                    }
                    
                    default:
                        $status = "<span class='stat unknown'>Unknown</span>";
                }
                echo "<tr data-id='{$v['id']}'>
                        <td>{$user->playername}</td>
                        <td>{$v['created']}</td>
                        <td>";
                if ($v['status'] == AffiliateRequests::STATUS_WAITING) {
                    echo "            <input type='button' class='accept' value='+'/>
                            <input type='button' class='decline' value='-'/>";
                }
                echo"   </td>
                        <td class='st'>$status</td>
                        <td><a href='#' class='details' data-id='{$v['id']}'>Details</a></td>
                    </tr>";
            }
        ?>
    </tbody>
</table>
    <div id='affiliateDetails' class='affiliatePopup'>
        <div class='wrap'>
        </div>
    </div>

<?php

    