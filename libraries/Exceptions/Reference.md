Error Reference
===============

All of the following errors are subclassed from the `BaseException` class.

| Error Code | Error Name                     | Error Message                                        |
| ---------- | ------------------------------ | ---------------------------------------------------- |
| 10001      | `ClassNotFoundException`       | Required class `$className` not found                |
| 10002      | `ControllerNotFoundException`  | Unable to find a suitable controller given the path  |
| 10003      | `ViewNotFoundException`        | Unable to find a suitable view given the path        |
| 10004      | `IncorrectModelException`      | Incorrect model provided to view                     |
| 10005      | `TemplateNotFoundException`    | Unable to locate template required to load this view |
| 10006      | `DatabaseUnavailableException` | All configured databases are unavailable             |
| 10007      | `QueryException`               | `$message`                                           |
