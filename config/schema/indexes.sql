create index users_branches_idx
    on users (year_branch_id, month_branch_id, day_branch_id, hour_branch_id);

create index users_email_pass_idx
    on users (email, password);

create index user_password_resets_pass_idx
    on user_password_resets (password, is_active);

create index user_images_uid_idx
    on user_images (user_id);

create index messages_thread_id_idx
    on messages (thread_id);

create index messages_uid_idx
    on messages (from_user_id, to_user_id);

create index user_email_changes_uid_idx
    on user_email_changes (user_id);

create index user_email_changes_pass_idx
    on user_email_changes (password, is_active);

create index user_deletes_uid_idx
    on user_deletes (user_id);

create index user_deletes_pass_idx
    on user_deletes (password, is_active);

create index user_logins_uid_idx
    on user_logins (user_id, is_successful);

create index user_clicks_uid_idx
    on user_clicks (user_id);
