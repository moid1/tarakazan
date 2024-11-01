@extends('layouts.admin')

@section('content')
    <div class="row new-chemist-parent-row">
        <div class="col-12 new-chemist-main ps-0 mt-3">Add New Business</div>
        <div class="col-12 ps-0 new-chemist-sub-div">
            <span class="new-chemist-sub">Business</span>
            <a href="./adminBusinesses.html">
                <img class="ps-1 pe-1" style="cursor: pointer" src="../images/Vector 175.svg" alt="" />
            </a>
            <span class="new-chemist-sub">Add New Business</span>
        </div>

        <form class="row new-chemist-child-row" enctype="multipart/form-data">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-12">
                        <div class="chemist-input-heading">Business Name</div>
                        <input type="text" name="business_name" id="business_name" required />
                    </div>
                    <div class="col-12">
                        <div class="chemist-input-heading">Business Email</div>
                        <input type="email" name="business_email" id="business_email" required />
                    </div>
                    <div class="col-12">
                        <div class="chemist-input-heading">Package</div>
                        <select name="package" id="package" required>
                            <option value="" disabled selected>Select Package</option>
                            <option value="basic">Basic</option>
                            <option value="premium">Premium</option>
                            <option value="enterprise">Enterprise</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <div class="chemist-input-heading">Logo</div>
                        <input type="file" name="logo" id="logo" accept="image/*" required />
                    </div>
                    <div class="col-12">
                        <div class="chemist-input-heading">Slug</div>
                        <input type="text" name="slug" id="slug" required />
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-12">
                        <div class="chemist-input-heading">Address</div>
                        <input type="text" name="address" id="address" required />
                    </div>
                    <div class="col-12">
                        <div class="chemist-input-heading">Country</div>
                        <input type="text" name="country" id="country" required />
                    </div>
                    <div class="col-12">
                        <div class="chemist-input-heading">Postal Code</div>
                        <input type="text" name="postal_code" id="postal_code" required />
                    </div>
                    <div class="col-12">
                        <div class="chemist-input-heading">Social Media Links</div>
                        <input type="url" name="facebook" id="facebook" placeholder="Facebook URL" />
                        <input type="url" name="twitter" id="twitter" placeholder="Twitter URL" />
                        <input type="url" name="instagram" id="instagram" placeholder="Instagram URL" />
                    </div>
                    <div class="col-12 add-chemist-btn-div mt-3 text-end">
                        <button class="chemist-cancel-btn me-2" type="button">Cancel</button>
                        <button class="chemist-add-btn" type="submit">Add</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
