      <ul class="nav nav-pills nav-stacked">
        <li <?php echo $this->request->here(false) == '/users/edit' ? 'class="active"' : '' ?>><a href="/users/edit">Update Profile</a></li>
        <li <?php echo $this->request->here(false) == '/images' ? 'class="active"' : '' ?>><a href="/images">Update Photos</a></li>
        <li <?php echo $this->request->here(false) == '/users/view' ? 'class="active"' : '' ?>><a href="/users/view">View My Profile</a></li>
        <li <?php echo $this->request->here(false) == '/users/email' || $this->request->here(false) == '/users/changeemail'  ? 'class="active"' : '' ?>><a href="/users/email">Update Email Address</a></li>
        <li <?php echo $this->request->here(false) == '/users/password' ? 'class="active"' : '' ?>><a href="/users/password">Change Password</a></li>
        <li <?php echo $this->request->here(false) == '/users/status' ? 'class="active"' : '' ?>><a href="/users/status">Update Profile Status</a></li>
      </ul>
