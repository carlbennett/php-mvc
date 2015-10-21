Error Reference
===============

All of the following errors are subclassed from the `BaseException` class.

| Error Code | Error Name                     | Error Message                                        |
| ---------- | ------------------------------ | ---------------------------------------------------- |
| 1          | `ClassNotFoundException`       | Required class `$className` not found                |
| 2          | `ControllerNotFoundException`  | Unable to find a suitable controller given the path  |
| 3          | `IncorrectModelException`      | Incorrect model provided to view                     |
| 4          | `TemplateNotFoundException`    | Unable to locate template required to load this view |
| 5          | `DatabaseUnavailableException` | All configured databases are unavailable             |
| 6          | `QueryException`               | `$message`                                           |
