<?php
include '../includes/layout.php';
$page_title = 'System Settings';
ob_start();
?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">System Settings</h1>
            <p class="text-muted">Manage system-wide configurations and preferences.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>General Settings</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="siteName" class="form-label">Site Name</label>
                            <input type="text" class="form-control" id="siteName" value="Learning Management System">
                        </div>
                        <div class="mb-3">
                            <label for="adminEmail" class="form-label">Admin Email</label>
                            <input type="email" class="form-control" id="adminEmail" value="admin@example.com">
                        </div>
                        <div class="mb-3">
                            <label for="timezone" class="form-label">Timezone</label>
                            <select class="form-select" id="timezone">
                                <option selected>UTC</option>
                                <option>GMT</option>
                                <option>WAT</option>
                                <option>EST</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user-shield me-2"></i>Security Settings</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="passwordPolicy" class="form-label">Password Policy</label>
                            <select class="form-select" id="passwordPolicy">
                                <option>Weak</option>
                                <option selected>Medium</option>
                                <option>Strong</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="sessionTimeout" class="form-label">Session Timeout (minutes)</label>
                            <input type="number" class="form-control" id="sessionTimeout" value="30">
                        </div>
                        <button type="submit" class="btn btn-primary">Update Security</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$page_content = ob_get_clean();
include '../includes/layout.php'; 