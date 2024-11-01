<div class="wrap container" id="ygv_wiz_wrap">
    <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-danger" role="alert">
            <?= $errorMessage ?>
        </div>
    <?php endif; ?>
    <form method="post">
        <div class="form-row">
            <div class="form-group col-md-9">
                <input type="text" class="form-control" name="media_id" value="" id="media_id" autocomplete="off" placeholder="Media ID">
                <small id="passwordHelpBlock" class="form-text text-muted">Put single video or playlist media ID</small>
            </div>
            <div class="form-group col-md-3">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>