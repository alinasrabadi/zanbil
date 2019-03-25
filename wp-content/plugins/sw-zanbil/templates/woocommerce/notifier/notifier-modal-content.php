<?php /** @var boolean $email_is_active */ ?>
<?php /** @var boolean $sms_is_active */ ?>
<?php /** @var boolean $email_checked */ ?>
<?php /** @var boolean $phone_checked */ ?>
<?php /** @var string $user_email */ ?>
<?php /** @var string $user_phone */ ?>

<div class="row d-flex align-stretch">
    <div class="col-md-4 hidden-sm hidden-xs">
        <div class="modal-body pl-0">
            <div class="modal-icon d-flex justify-center align-center"><img src="data:image/svg+xml;base64,
PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNTEyIDUxMiIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNTEyIDUxMjsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSI1MTIiIGhlaWdodD0iNTEyIiBjbGFzcz0iIj48Zz48Zz4KCTxwYXRoIHN0eWxlPSJmaWxsOiNGRkQ3MkUiIGQ9Ik00MjYuMjksMzEyLjI1NGMtMzguODA4LTIyLjM5Ni01Mi41MDQtODMuMTQyLTU3LjA4Ni0xNDIuNzczICAgYy0zLjQ2My00NS4wODctMzMuMTM1LTgyLjI3LTcyLjU1Ny05Ny43MzFjLTM5LjQyMSwxNS40NjEtNjkuMDkxLDUyLjY0NS03Mi41NTYsOTcuNzMxICAgYy00LjU4MSw1OS42MzEtMTguMjc5LDEyMC4zNzctNTcuMDg2LDE0Mi43NzNjLTEzLjkwNCw4LjAyMy0xMi40NjgsNDQuNTg2LTcuNjM3LDQ5LjQxNGg5Ni42MjloODEuMjk2aDk2LjYzMyAgIEM0MzguNzU3LDM1Ni44NCw0NDAuMTkyLDMyMC4yNzUsNDI2LjI5LDMxMi4yNTR6IiBkYXRhLW9yaWdpbmFsPSIjRkZENzJFIiBjbGFzcz0iIj48L3BhdGg+Cgk8Zz4KCQk8cGF0aCBzdHlsZT0iZmlsbDojRkY2QjM5IiBkPSJNMjgwLjUzMyw0MTAuMTRjMTcuNjc3LTguOTY1LDI5Ljc5OS0yNy4yOTgsMjkuNzk5LTQ4LjQ3MmgtNS4yNjdoLTQ5LjA2N2gtNS4yNjMgICAgQzI1MC43MzcsMzgyLjg0MiwyNjIuODU4LDQwMS4xNzUsMjgwLjUzMyw0MTAuMTR6IiBkYXRhLW9yaWdpbmFsPSIjRkY2QjM5IiBjbGFzcz0iIj48L3BhdGg+CgkJPHBhdGggc3R5bGU9ImZpbGw6I0ZGNkIzOSIgZD0iTTI2Ny41NTcsMTMuMzA4Yy04LjkzNyw0LjMyLTE1LjEyNiwxMy40ODEtMTUuMTI2LDI0LjA0YzAsMTAuNTU5LDYuMTksMTkuNzE5LDE1LjEyNiwyNC4wMzkgICAgYzguOTM3LTQuMzIsMTUuMTI1LTEzLjQ4MSwxNS4xMjUtMjQuMDM5QzI4Mi42ODIsMjYuNzg4LDI3Ni40OTMsMTcuNjI3LDI2Ny41NTcsMTMuMzA4eiIgZGF0YS1vcmlnaW5hbD0iI0ZGNkIzOSIgY2xhc3M9IiI+PC9wYXRoPgoJPC9nPgo8L2c+PGc+Cgk8cGF0aCBzdHlsZT0iZmlsbDojMzMzMzMzIiBkPSJNNjUuMTkyLDI3Mi44NzJjLTMuOTgtNC4zNDItMTAuNzI3LTQuNjQxLTE1LjA3MS0wLjY1OWMtMS4yMzMsMS4xMy0yLjUwOSwyLjI3MS0zLjgyNSwzLjQyNyAgIGMtNC40MjUsMy44ODgtNC44NjMsMTAuNjI3LTAuOTc1LDE1LjA1M2MyLjExMSwyLjQwMSw1LjA1NiwzLjYyOCw4LjAxOSwzLjYyOGMyLjUsMCw1LjAxLTAuODc0LDcuMDM2LTIuNjUzICAgYzEuNDMxLTEuMjU3LDIuODE3LTIuNDk4LDQuMTU2LTMuNzI2QzY4Ljg3NiwyODMuOTYzLDY5LjE3MiwyNzcuMjE1LDY1LjE5MiwyNzIuODcyeiIgZGF0YS1vcmlnaW5hbD0iIzAwMDAwMyIgY2xhc3M9ImFjdGl2ZS1wYXRoIiBkYXRhLW9sZF9jb2xvcj0iIzIyMjIyMiI+PC9wYXRoPgoJPHBhdGggc3R5bGU9ImZpbGw6IzMzMzMzMyIgZD0iTTcyLjMzOSwyNjUuNDE3YzEuODU2LDEuMjkzLDMuOTc5LDEuOTEzLDYuMDgzLDEuOTEzYzMuMzczLDAsNi42OTEtMS41OTcsOC43NjUtNC41NzUgICBjMTcuNTYzLTI1LjIzOCwyMC4yMDYtNTAuMTgsMjMuNzA0LTk1LjcyNWMwLjQ1Mi01Ljg3NC0zLjk0My0xMS4wMDItOS44MTktMTEuNDUzYy01Ljg5MS0wLjQ1MS0xMS4wMDEsMy45NDYtMTEuNDUyLDkuODE4ICAgYy0zLjI5Niw0Mi45MDItNS41MTksNjQuNDQ1LTE5Ljk0Myw4NS4xNzRDNjYuMzA5LDI1NS40MDQsNjcuNTAzLDI2Mi4wNTMsNzIuMzM5LDI2NS40MTd6IiBkYXRhLW9yaWdpbmFsPSIjMDAwMDAzIiBjbGFzcz0iYWN0aXZlLXBhdGgiIGRhdGEtb2xkX2NvbG9yPSIjMjIyMjIyIj48L3BhdGg+Cgk8cGF0aCBzdHlsZT0iZmlsbDojMzMzMzMzIiBkPSJNMzk4LjMzNiwxNDcuODMyYzEuMDY5LDUuMDEyLDUuNDk1LDguNDQ2LDEwLjQyMiw4LjQ0NmMwLjczNiwwLDEuNDg0LTAuMDc3LDIuMjM0LTAuMjM3ICAgYzUuNzYtMS4yMjgsOS40MzgtNi44OTQsOC4yMDgtMTIuNjU1Yy0wLjQzOS0yLjA2My0wLjkyNS00LjE0Mi0xLjQ0Mi02LjE3N2MtMS40NTItNS43MDktNy4yNTktOS4xNjItMTIuOTY2LTcuNzEgICBjLTUuNzA5LDEuNDUyLTkuMTYxLDcuMjU3LTcuNzA5LDEyLjk2NkMzOTcuNTMyLDE0NC4yMzMsMzk3Ljk1NCwxNDYuMDM5LDM5OC4zMzYsMTQ3LjgzMnoiIGRhdGEtb3JpZ2luYWw9IiMwMDAwMDMiIGNsYXNzPSJhY3RpdmUtcGF0aCIgZGF0YS1vbGRfY29sb3I9IiMyMjIyMjIiPjwvcGF0aD4KCTxwYXRoIHN0eWxlPSJmaWxsOiMzMzMzMzMiIGQ9Ik00NjUuNDg0LDI3NS40NTNjLTMxLjIyNC0yNS45NjktMzguMDgzLTUxLjI2OS00Mi40MzMtMTAxLjc2OCAgIGMtMC41MDctNS44Ny01LjY3OS0xMC4yMjEtMTEuNTQzLTkuNzExYy01Ljg2OSwwLjUwNi0xMC4yMTcsNS42NzQtOS43MSwxMS41NDJjNC42OTgsNTQuNTMxLDEzLjM4Myw4NS44NDksNTAuMDQ2LDExNi4zMzkgICBjMS45OTQsMS42NTgsNC40MTEsMi40NjYsNi44MTUsMi40NjZjMy4wNiwwLDYuMDk4LTEuMzEsOC4yMDgtMy44NDZDNDcwLjYzMiwyODUuOTQ1LDQ3MC4wMTMsMjc5LjIyLDQ2NS40ODQsMjc1LjQ1M3oiIGRhdGEtb3JpZ2luYWw9IiMwMDAwMDMiIGNsYXNzPSJhY3RpdmUtcGF0aCIgZGF0YS1vbGRfY29sb3I9IiMyMjIyMjIiPjwvcGF0aD4KCTxwYXRoIHN0eWxlPSJmaWxsOiMzMzMzMzMiIGQ9Ik00NDEuOTA0LDMxNC4yMzljLTAuMTQyLTAuMjg0LTAuMjk1LTAuNTYtMC40NjMtMC44MjhjLTIuNTc5LTQuNi01Ljg2Ny04LjExNC05LjgyMy0xMC4zOTYgICBjLTI4Ljc4Ny0xNi42MTMtNDYuMjA4LTYxLjgxNi01MS43ODEtMTM0LjM1MmMtNC4xMzMtNTMuOC00Mi40OTQtOTcuODk1LTkyLjQwNi0xMTEuMTg3YzMuNzM4LTUuODEzLDUuOTE1LTEyLjcyLDUuOTE1LTIwLjEyOSAgIEMyOTMuMzQ3LDE2Ljc1NCwyNzYuNTkzLDAsMjU1Ljk5OCwwYy0yMC41OTIsMC0zNy4zNDYsMTYuNzU0LTM3LjM0NiwzNy4zNDhjMCw3LjQwOSwyLjE3OSwxNC4zMTUsNS45MTUsMjAuMTI5ICAgYy00OS45MTIsMTMuMjkxLTg4LjI3Myw1Ny4zODctOTIuNDA4LDExMS4xODdjLTUuNTczLDcyLjUzNi0yMi45OTQsMTE3LjczOC01MS43NzksMTM0LjM1MiAgIGMtOC4zMzcsNC44MTEtMTMuNzU1LDE1LjAyNy0xNS42NjUsMjkuNTQ4Yy0xLjIzOSw5LjQyNi0xLjYyMSwyOS4yMTcsNS44MTcsMzYuNjVjMiwxLjk5OSw0LjcxMywzLjEyMiw3LjUzOSwzLjEyMmgxMTMuODIzICAgYzUuMTA0LDMwLjc4MSwzMS45LDU0LjMzMiw2NC4xMDcsNTQuMzMyYzMyLjIwNiwwLDU5LjAwMS0yMy41NTEsNjQuMTA3LTU0LjMzMmgxMTMuODIxYzIuODI3LDAsNS41MzktMS4xMjMsNy41MzktMy4xMjIgICBjNy40NC03LjQzNyw3LjA1Ni0yNy4yMzQsNS44MTQtMzYuNjYzQzQ0Ni4zMywzMjUuMzM4LDQ0NC41MTMsMzE5LjE5MSw0NDEuOTA0LDMxNC4yMzl6IE0yNTUuOTk4LDIxLjMzMyAgIGM4LjgzMSwwLDE2LjAxNSw3LjE4NCwxNi4wMTUsMTYuMDE1YzAsOC44My03LjE4MywxNi4wMTQtMTYuMDE1LDE2LjAxNGMtOC44MjksMC0xNi4wMTMtNy4xODQtMTYuMDEzLTE2LjAxNCAgIEMyMzkuOTg2LDI4LjUxNywyNDcuMTcsMjEuMzMzLDI1NS45OTgsMjEuMzMzeiBNMjU1Ljk5OCw0MDUuMzMyYy0yMC4zOTgsMC0zNy41NjktMTQuMDYxLTQyLjM0MS0zMi45OThoODQuNjgxICAgQzI5My41NjcsMzkxLjI3MiwyNzYuMzk2LDQwNS4zMzIsMjU1Ljk5OCw0MDUuMzMyeiBNNDI2LjIzNCwzNTEuMDAySDg1Ljc2NGMtMC40NDItMy40ODctMC42NzUtOC41NDItMC4wNjctMTQuMjM1ICAgYzEuMDIxLTkuNTMyLDMuNzU2LTE0LjM1Niw1LjM0Ni0xNS4yNzVjMzUuNzQ4LTIwLjYzMSw1Ni4xNTYtNzAuMDg3LDYyLjM4Ny0xNTEuMTk0YzQuMTE4LTUzLjYwOSw0OS4xNzMtOTUuNjAzLDEwMi41NjgtOTUuNjAzICAgYzUzLjM5NiwwLDk4LjQ1LDQxLjk5NSwxMDIuNTY4LDk1LjYwM2MyLjk5OCwzOS4wMTksOS4yODUsNzAuNjkxLDE4Ljk3NSw5NS4zNzRoLTQwLjFjLTUuODksMC0xMC42NjcsNC43NzUtMTAuNjY3LDEwLjY2NyAgIGMwLDUuODkxLDQuNzc3LDEwLjY2NywxMC42NjcsMTAuNjY3aDUwLjMyM2M0LjgyMiw4LjI0OSwxMC4yMjEsMTUuMzUsMTYuMTk4LDIxLjMzM0gyOTcuNTk4Yy01Ljg4OSwwLTEwLjY2Nyw0Ljc3NS0xMC42NjcsMTAuNjY3ICAgYzAsNS44OTEsNC43NzgsMTAuNjY3LDEwLjY2NywxMC42NjdoMTI3LjQ1NGMwLjUxMywxLjk4OCwwLjk1Myw0LjMzOCwxLjI0OCw3LjA5MyAgIEM0MjYuOTA5LDM0Mi40NTksNDI2LjY3NSwzNDcuNTE1LDQyNi4yMzQsMzUxLjAwMnoiIGRhdGEtb3JpZ2luYWw9IiMwMDAwMDMiIGNsYXNzPSJhY3RpdmUtcGF0aCIgZGF0YS1vbGRfY29sb3I9IiMyMjIyMjIiPjwvcGF0aD4KCTxwYXRoIHN0eWxlPSJmaWxsOiMzMzMzMzMiIGQ9Ik0zNjIuNjYzLDQ5MC42NjdsLTIxMy4zMzMtMC4wMDRjLTUuODg5LDAtMTAuNjY3LDQuNzc1LTEwLjY2NywxMC42NjcgICBjMCw1Ljg5LDQuNzc1LDEwLjY2NywxMC42NjcsMTAuNjY3TDM2Mi42NjMsNTEyYzUuODkxLDAsMTAuNjY3LTQuNzc1LDEwLjY2Ny0xMC42NjcgICBDMzczLjMyOSw0OTUuNDQzLDM2OC41NTUsNDkwLjY2NywzNjIuNjYzLDQ5MC42Njd6IiBkYXRhLW9yaWdpbmFsPSIjMDAwMDAzIiBjbGFzcz0iYWN0aXZlLXBhdGgiIGRhdGEtb2xkX2NvbG9yPSIjMjIyMjIyIj48L3BhdGg+Cgk8cGF0aCBzdHlsZT0iZmlsbDojMzMzMzMzIiBkPSJNMjU5LjE5OCwzMDguMzM5aC02LjRjLTUuODkxLDAtMTAuNjY3LDQuNzc1LTEwLjY2NywxMC42NjdjMCw1Ljg5MSw0Ljc3NSwxMC42NjcsMTAuNjY3LDEwLjY2N2g2LjQgICBjNS44ODksMCwxMC42NjctNC43NzUsMTAuNjY3LTEwLjY2N0MyNjkuODY1LDMxMy4xMTUsMjY1LjA4OSwzMDguMzM5LDI1OS4xOTgsMzA4LjMzOXoiIGRhdGEtb3JpZ2luYWw9IiMwMDAwMDMiIGNsYXNzPSJhY3RpdmUtcGF0aCIgZGF0YS1vbGRfY29sb3I9IiMyMjIyMjIiPjwvcGF0aD4KPC9nPjwvZz4gPC9zdmc+" /></div>
        </div>
    </div>
    <div class="col-md-8 modal-light-bg">
        <div class="modal-body pr-0">
            <div class="form-group modal-form-title">
                <label>با پر کردن فرم زیر هر زمان که این محصول موجود شد از طریق ایمیل یا ارسال پیامک به شما اطلاع رسانی خواهد شد.</label>
            </div>
            
            <div class="modal-form-alert alert alert-danger hidden"></div>

            <div class="form-group modal-form-title">
                <label>از طریق:</label>
            </div>

            <div class="form-group">
                <?php if ( $email_is_active ): ?>
                    <div class="checkbox" style="margin: 40px;">
                        <label for="tcw_notifier[email][on]">
                            <input type="checkbox" id="tcw_notifier[email][on]" name="tcw_notifier[email][on]" value="true" <?= checked( $email_checked, true ); ?>>
                            <?= sprintf( __( 'Email to %s', TCW_TEXTDOMAIN ), null ); ?>
                        </label>
                        <div class="form-checkbox-input">
                            <input type="text" class="form-control" id="tcw_notifier[email][input]" name="tcw_notifier[email][input]" value="<?= $user_email; ?>" placeholder="example@domain.com" dir="ltr">
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ( $sms_is_active ): ?>
                    <div class="checkbox" style="margin: 40px;">
                        <label for="tcw_notifier[phone][on]">
                            <input type="checkbox" id="tcw_notifier[phone][on]" name="tcw_notifier[phone][on]" value="true" <?= checked( $phone_checked, true ); ?>>
                            <?= sprintf( esc_html__( 'SMS to %s', TCW_TEXTDOMAIN ), null ); ?>
                        </label>
                        <div class="form-checkbox-input">
                            <input type="text" class="form-control" id="tcw_notifier[phone][input]" name="tcw_notifier[phone][input]" value="<?= $user_phone; ?>" placeholder="09xxxxxxxxx" dir="ltr">
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="modal-footer-wrap text-left">
                <?php include 'notifier-modal-footer.php'; ?>
            </div><!-- /modal-footer-wrap -->
        </div>
    </div>
</div>

