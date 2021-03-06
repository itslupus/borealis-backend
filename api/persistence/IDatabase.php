<?php
    interface IDatabase {
        function get_user(int $id);
        function get_token_by_id(int $id);
        function get_token_by_token(string $token);

        function insert_new_user(int $id, int $first_term, int $last_term);
        function insert_new_token(int $id, Token $token);

        function update_user(int $id, int $last_term);

        function update_token_token(int $id, string $token);

        function delete_token($id);
    }
?>