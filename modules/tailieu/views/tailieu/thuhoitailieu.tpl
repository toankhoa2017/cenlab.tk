<div class="row">
    <div class="col-xs-12">
        <form class="form-horizontal" role="form" id="validation-form" action="{site_url()}tailieu/tailieu/thuhoitailieu" method="post" name="thuhoitailieu">
            <input type="hidden" name="tai_lieu_id" id="tai_lieu_id" value={$tai_lieu->tai_lieu_id} />
            <input type="hidden" name="tai_lieu_code" id="tai_lieu_code" value={$tai_lieu->tai_lieu_code} />
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
                <div class="col-sm-3">
                    <div class="checkbox">
                        <label>
                            <input name="thu_hoi_tai_lieu" id="thu_hoi_tai_lieu" type="checkbox" class="ace">
                            <span class="lbl"> Thu hồi tài liệu này</span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <button class="btn btn-info" type="submit">
                        <i class="ace-icon fa fa-check bigger-110"></i>
                        Đồng Ý
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>    