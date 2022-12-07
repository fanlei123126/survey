</head>

<body class="fixed-sidebar full-height-layout gray-bg">
<div class="ibox-content">
    <form class="form-horizontal m-t" id="ff">
        <?php $systemRoleInfo = $this->system_role_model->getRoleInfoById($accountInfo['system_role_id'], 1);
            $userSystemId = isset($systemRoleInfo['system_id']) ? $systemRoleInfo['system_id'] : 0;
            foreach ($systemList as $system) {
            ?>
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo $system['name']; ?>：</label>
                <?php if ($system['Id'] == 1) { ?>
                    <div class="col-sm-8">
                        <p>
                            系统角色：<?php echo isset($systemRoleInfo['system_role_name']) ? $systemRoleInfo['system_role_name'] : ""; ?>
                        </p>
                    </div>
                <?php } elseif ($system['Id'] == 2) {
                    if($systemRoleInfo){ ?>
                        <div class="col-sm-8">
                            <p>
                                系统角色：<?php echo isset($systemRoleInfo['system_role_name']) ? $systemRoleInfo['system_role_name'] : ""; ?>
                            </p>
                        </div>
                <?php }
                    $groupMemberList = $this->research_group_member_list_model->getGroupMemberListByAccountId($accountInfo['account_id']);
                    if ($groupMemberList) {
                        ?>
                        <div class="col-sm-8">
                            <?php foreach ($groupMemberList as $group) {
                                $groupInfo = $this->research_group_list_model->getRearchGroupInfoById($group['research_group_id']);
                                $roleInfo = $this->research_group_role_list_model->getGroupRoleById($group['research_group_role_id']);
                                ?>
                                <p>
                                    课题组：<?php echo isset($groupInfo['group_name']) ? $groupInfo['group_name'] : ""; ?>
                                    <br/>
                                    课题角色：<?php echo isset($roleInfo['group_role_name']) ? $roleInfo['group_role_name'] : ""; ?>
                                </p>
                            <?php } ?>
                        </div>
                    <?php }
                } elseif ($system['Id'] == 3) { ?>
                    <div class="col-sm-8">
                        <p>
                            暂时无法获取
                        </p>
                    </div>
                <?php } ?>
            </div>
        <?php }
        ?>
    </form>
</div>

<?php $this->load->view('common/js')?>