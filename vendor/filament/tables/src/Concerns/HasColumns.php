<?php

namespace Filament\Tables\Concerns;

use Closure;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\Contracts\Editable;
use Filament\Tables\Contracts\HasRelationshipTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait HasColumns
{
    protected array $cachedTableColumns;

    public function cacheTableColumns(): void
    {
        $this->cachedTableColumns = [];

        foreach ($this->getTableColumns() as $column) {
            $column->table($this->getCachedTable());

            $this->cachedTableColumns[$column->getName()] = $column;
        }
    }

    public function callTableColumnAction(string $name, string $recordKey)
    {
        $record = $this->getTableRecord($recordKey);

        if (! $record) {
            return;
        }

        $column = $this->getCachedTableColumn($name);

        if (! $column) {
            return;
        }

        if ($column->isHidden()) {
            return;
        }

        $action = $column->getAction();

        if (! ($action instanceof Closure)) {
            return;
        }

        return $column->record($record)->evaluate($action);
    }

    public function getCachedTableColumns(): array
    {
        return $this->cachedTableColumns;
    }

    public function getCachedTableColumn(string $name): ?Column
    {
        return $this->getCachedTableColumns()[$name] ?? null;
    }

    public function setColumnValue(string $column, string $record, $input): ?array
    {
        $columnName = $column;
        $column = $this->getCachedTableColumn($column);

        if (! ($column instanceof Editable)) {
            return null;
        }

        $record = $this->getTableRecord($record);

        if (! $record) {
            return null;
        }

        $column->record($record);

        if ($column->isDisabled()) {
            return null;
        }

        try {
            $column->validate($input);
        } catch (ValidationException $exception) {
            return [
                'error' => $exception->getMessage(),
            ];
        }

        if ($columnRelationship = $column->getRelationship($record)) {
            $record = $columnRelationship->getResults();
            $columnName = $column->getRelationshipTitleColumnName();
        } elseif (
            $this instanceof HasRelationshipTable &&
            (($tableRelationship = $this->getRelationship()) instanceof BelongsToMany) &&
            in_array($columnName, $tableRelationship->getPivotColumns())
        ) {
            $record = $record->{$tableRelationship->getPivotAccessor()};
        } else {
            $columnName = (string) Str::of($columnName)->replace('.', '->');
        }

        if (! ($record instanceof Model)) {
            return null;
        }

        $record->setAttribute($columnName, $input);
        $record->save();

        return null;
    }

    protected function getTableColumns(): array
    {
        return [];
    }
}
