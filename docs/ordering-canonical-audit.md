# Ordering canonical audit

Generated from cumulative snapshot wave 06.

- Generated at UTC: 2026-03-21T02:46:13+00:00
- Total violations: 0
- root_forbidden: 0
- src_forbidden: 0
- src_competing_tree: 0
- order_dir_depth: 0
- tests_forbidden: 0
- todo_token: 0
- stub_token: 0
- empty_catch: 0

## Wave 06 focus

This wave removes all remaining forbidden `.../Order/...` directory paths outside the allowed
exception scope `src/Entity/Order/...`.

The result is a fully-clean owner-canon scan for the current cumulative snapshot, so the next
waves can shift from structural deletion toward runtime restoration and Symfony-oriented rebuild.
