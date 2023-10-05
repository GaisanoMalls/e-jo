<div class="modal fade filter__user__with__roles__modal" id="filterUserWithRolesModal" tabindex="-1"
    aria-labelledby="filterUserWithRolesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal__content">
            <div class="modal-header mb-3 p-0 modal__header border-0">
                <h6 class="modal-title modal__title" id="filterUserWithRolesModalLabel">Filter user with roles</h6>
                <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                    <i class="fa-sharp fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-2">
                        <label for="branch" class="form-label form__field__label">Branch</label>
                        <select name="branch" data-search="true" data-silent-initial-value-set="true">
                            <option value="" selected disabled>Choose a branch</option>
                            <option value=""></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-2">
                        <label for="role" class="form-label form__field__label">Role</label>
                        <select name="role" data-search="true" data-silent-initial-value-set="true">
                            <option value="" selected disabled>Choose a role</option>
                            <option value=""></option>
                        </select>
                    </div>
                </div>
                <div class="col-12 px-2 mt-2">
                    <div class="modal-footer modal__footer p-0 justify-content-between border-0 gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <button type="submit" class="btn m-0 btn__modal__footer btn__send">Filter</button>
                            <button type="button" class="btn m-0 btn__modal__footer btn__cancel" id="btnCloseModal"
                                data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>