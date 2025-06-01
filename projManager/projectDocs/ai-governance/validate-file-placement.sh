#!/bin/bash

# File Placement Validation Script
# Ensures compliance with master-index.json routing patterns
# Run this before any commit to validate file organization

echo "🔍 AI Agent File Placement Validation"
echo "======================================"

ERRORS_FOUND=0

# Check for PLAN files in wrong locations
echo "Checking for misplaced PLAN files..."
MISPLACED_PLANS=$(find projManager/projectDocs/decisions/ -name "PLAN-*" 2>/dev/null)
if [ ! -z "$MISPLACED_PLANS" ]; then
    echo "❌ PLAN files found in decisions/ directory:"
    echo "$MISPLACED_PLANS"
    echo "   → Should be in: projManager/projectDocs/execution/planning/"
    ERRORS_FOUND=1
fi

# Check for implementation files in requirements
echo "Checking for implementation files in requirements..."
MISPLACED_IMPL=$(find projManager/projectDocs/requirements/ -name "*implementation*" -o -name "*spec*" -o -name "*plan*" 2>/dev/null)
if [ ! -z "$MISPLACED_IMPL" ]; then
    echo "❌ Implementation files found in requirements/ directory:"
    echo "$MISPLACED_IMPL"
    echo "   → Should be in: projManager/projectDocs/execution/"
    ERRORS_FOUND=1
fi

# Check for UX/UI files in wrong locations
echo "Checking for UX/UI files in wrong locations..."
MISPLACED_UX=$(find projManager/projectDocs/knowledge/ -name "*UX*" -o -name "*UI*" -o -name "*design*" 2>/dev/null)
if [ ! -z "$MISPLACED_UX" ]; then
    echo "❌ UX/UI files found in knowledge/ directory:"
    echo "$MISPLACED_UX"
    echo "   → Should be in: projManager/projectDocs/execution/planning/"
    ERRORS_FOUND=1
fi

# Check for ADR files in wrong locations
echo "Checking for ADR files in wrong locations..."
MISPLACED_ADR=$(find projManager/projectDocs/execution/ -name "ADR-*" 2>/dev/null)
if [ ! -z "$MISPLACED_ADR" ]; then
    echo "❌ ADR files found in execution/ directory:"
    echo "$MISPLACED_ADR"
    echo "   → Should be in: projManager/projectDocs/decisions/architectural/"
    ERRORS_FOUND=1
fi

# Check for metrics in wrong locations
echo "Checking for metrics files in wrong locations..."
MISPLACED_METRICS=$(find projManager/projectDocs/execution/ -name "*metric*" -o -name "*dashboard*" -o -name "*report*" 2>/dev/null)
if [ ! -z "$MISPLACED_METRICS" ]; then
    echo "❌ Metrics files found in execution/ directory:"
    echo "$MISPLACED_METRICS"
    echo "   → Should be in: projManager/projectDocs/analytics/metrics/"
    ERRORS_FOUND=1
fi

# Check for documentation at root level (except README.md)
echo "Checking for documentation at root level..."
ROOT_DOCS=$(find . -maxdepth 1 -name "*.md" -not -name "README.md" 2>/dev/null)
if [ ! -z "$ROOT_DOCS" ]; then
    echo "❌ Documentation files found at root level:"
    echo "$ROOT_DOCS"
    echo "   → Should be in appropriate projManager/projectDocs/ subdirectories"
    ERRORS_FOUND=1
fi

# Summary
echo ""
echo "======================================"
if [ $ERRORS_FOUND -eq 0 ]; then
    echo "✅ File placement validation PASSED"
    echo "   All files are properly organized according to master-index.json routing patterns"
    exit 0
else
    echo "❌ File placement validation FAILED"
    echo ""
    echo "🛠️  FIX REQUIRED:"
    echo "   1. Move misplaced files to correct directories"
    echo "   2. Use the routing decision matrix in AI-AGENT-MANDATORY-PROTOCOLS.md"
    echo "   3. Run this script again to verify fixes"
    echo ""
    echo "📚 Routing Reference:"
    echo "   - Implementation plans → execution/planning/"
    echo "   - Architectural decisions → decisions/architectural/"
    echo "   - Requirements → requirements/refined/"
    echo "   - Tasks → tasks/pending|completed/"
    echo "   - Best practices → knowledge/patterns/"
    echo "   - Metrics → analytics/metrics/"
    exit 1
fi
