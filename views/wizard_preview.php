<div class="wrap container" id="ygv_wiz_wrap">
    <form>
        <div class="form-group">
            <select class="form-control form-control-sm" id="code-type">
                <?php foreach ($typeList as $value => $label): ?>
                    <option value="<?= $value ?>"><?= $label ?></option>
                <?php endforeach; ?>
            </select>
            <small id="code-type-help" class="form-text text-muted">Type of embed code</small>
        </div>
        <div class="row d-none" id="iframe-settings">
            <div class="col-6 col-lg-6 form-group">
                <input class="form-control form-control-sm size-inp" value="100%" id="width" type="text"/>
                <small id="height-help" class="form-text text-muted">Width: 100% or integer</small>
            </div>
            <div class="col-6 col-lg-6 form-group">
                <input class="form-control form-control-sm size-inp" value="100%" id="height" type="text"/>
                <small id="height-help" class="form-text text-muted">Height: 100% or integer</small>
            </div>
        </div>
        <div class="form-group">
            <label for="code-type">Shortcode</label>
            <textarea class="form-control form-control-sm code-textarea active" id="js-code" readonly>[ygplayer]<?= esc_url($jsUrl) ?>[/ygplayer]</textarea>
            <textarea class="form-control form-control-sm code-textarea" id="html-code" readonly>[ygplayer type="iframe" width="100%" height="100%"]<?= esc_url($htmlUrl) ?>[/ygplayer]</textarea>
        </div>
        <div class="row">
            <div class="col align-self-center">
                <button class="btn btn-default btn-sm" id="inserttopost" rel="[ygplayer]<?= esc_url($jsUrl) ?>[/ygplayer]">Insert Into Editor</button>
            </div>
        </div>
    </form>
    <div class="row mt-5">
        <div class="col-12">
            <script src="<?= esc_url($jsUrl) ?>"></script>
        </div>
    </div>
</div>