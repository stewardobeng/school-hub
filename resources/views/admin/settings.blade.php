@extends('layouts.dashboard', ['title' => 'Settings', 'userRole' => 'admin'])

@section('content')
<div x-data="{ 
    activeTab: 'general',
    isLoading: false,
    isBackingUp: false,
    successMessage: '',
    errorMessage: '',
    handleSaveSettings() {
        this.isLoading = true;
        this.successMessage = '';
        this.errorMessage = '';
        setTimeout(() => {
            this.successMessage = 'Settings saved successfully!';
            this.isLoading = false;
            setTimeout(() => this.successMessage = '', 3000);
        }, 500);
    },
    createBackup() {
        this.isBackingUp = true;
        this.successMessage = '';
        this.errorMessage = '';
        setTimeout(() => {
            this.successMessage = 'Backup created successfully!';
            this.isBackingUp = false;
            setTimeout(() => this.successMessage = '', 3000);
        }, 1500);
    }
}" class="space-y-6">
    <!-- Tabs -->
    <div class="border-b border-border">
        <div class="flex gap-4">
            <button @click="activeTab = 'general'" 
                    :class="activeTab === 'general' ? 'border-b-2 border-primary text-foreground' : 'text-muted-foreground'"
                    class="pb-2 px-1 font-medium transition-colors">
                General
            </button>
            <button @click="activeTab = 'notifications'" 
                    :class="activeTab === 'notifications' ? 'border-b-2 border-primary text-foreground' : 'text-muted-foreground'"
                    class="pb-2 px-1 font-medium transition-colors">
                Notifications
            </button>
            <button @click="activeTab = 'security'" 
                    :class="activeTab === 'security' ? 'border-b-2 border-primary text-foreground' : 'text-muted-foreground'"
                    class="pb-2 px-1 font-medium transition-colors">
                Security
            </button>
            <button @click="activeTab = 'system'" 
                    :class="activeTab === 'system' ? 'border-b-2 border-primary text-foreground' : 'text-muted-foreground'"
                    class="pb-2 px-1 font-medium transition-colors">
                System
            </button>
        </div>
    </div>

    <!-- General Settings -->
    <div x-show="activeTab === 'general'" class="space-y-6">
        <div class="bg-card rounded-xl p-6 shadow-sm border border-border/50">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <h3 class="text-lg font-semibold">General Settings</h3>
            </div>
            <p class="text-sm text-muted-foreground mb-6">Manage your school's general information and preferences</p>
            
            <form @submit.prevent="handleSaveSettings()" class="space-y-6">
                <div x-show="successMessage" class="p-3 bg-green-100 border border-green-400 text-green-700 rounded-md text-sm" x-text="successMessage"></div>
                <div x-show="errorMessage" class="p-3 bg-red-100 border border-red-400 text-red-700 rounded-md text-sm" x-text="errorMessage"></div>
                <div class="space-y-2">
                    <label for="school-name" class="text-sm font-medium">School Name</label>
                    <input type="text" id="school-name" placeholder="Enter school name" value="School Hub" 
                           class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                </div>
                <div class="space-y-2">
                    <label for="school-email" class="text-sm font-medium">School Email</label>
                    <input type="email" id="school-email" placeholder="admin@school.edu" value="admin@school.edu" 
                           class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                </div>
                <div class="space-y-2">
                    <label for="school-phone" class="text-sm font-medium">School Phone</label>
                    <input type="tel" id="school-phone" placeholder="+1 234-567-8900" value="+1 234-567-8900" 
                           class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                </div>
                <div class="space-y-2">
                    <label for="school-address" class="text-sm font-medium">School Address</label>
                    <input type="text" id="school-address" placeholder="123 School Street, City, State" 
                           class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label for="timezone" class="text-sm font-medium">Timezone</label>
                        <select id="timezone" class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                            <option value="est" selected>Eastern Time (EST)</option>
                            <option value="pst">Pacific Time (PST)</option>
                            <option value="cst">Central Time (CST)</option>
                            <option value="mst">Mountain Time (MST)</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label for="language" class="text-sm font-medium">Language</label>
                        <select id="language" class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                            <option value="en" selected>English</option>
                            <option value="es">Spanish</option>
                            <option value="fr">French</option>
                        </select>
                    </div>
                </div>
                <button type="submit" :disabled="isLoading" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90 transition-colors disabled:opacity-50">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span x-show="!isLoading">Save Changes</span>
                        <span x-show="isLoading">Saving...</span>
                    </span>
                </button>
            </form>
        </div>
    </div>

    <!-- Notifications Settings -->
    <div x-show="activeTab === 'notifications'" class="space-y-6">
        <div class="bg-card rounded-xl p-6 shadow-sm border border-border/50">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <h3 class="text-lg font-semibold">Notification Preferences</h3>
            </div>
            <p class="text-sm text-muted-foreground mb-6">Configure how you receive notifications</p>
            
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <label class="text-sm font-medium">Email Notifications</label>
                        <p class="text-sm text-muted-foreground">Receive notifications via email</p>
                    </div>
                    <input type="checkbox" checked class="w-4 h-4 text-primary rounded focus:ring-primary">
                </div>
                <hr class="border-border">
                <div class="flex items-center justify-between">
                    <div>
                        <label class="text-sm font-medium">New Student Enrollment</label>
                        <p class="text-sm text-muted-foreground">Get notified when a new student enrolls</p>
                    </div>
                    <input type="checkbox" checked class="w-4 h-4 text-primary rounded focus:ring-primary">
                </div>
                <hr class="border-border">
                <div class="flex items-center justify-between">
                    <div>
                        <label class="text-sm font-medium">Payment Reminders</label>
                        <p class="text-sm text-muted-foreground">Receive reminders for pending payments</p>
                    </div>
                    <input type="checkbox" checked class="w-4 h-4 text-primary rounded focus:ring-primary">
                </div>
                <hr class="border-border">
                <div class="flex items-center justify-between">
                    <div>
                        <label class="text-sm font-medium">Attendance Alerts</label>
                        <p class="text-sm text-muted-foreground">Get alerts for low attendance rates</p>
                    </div>
                    <input type="checkbox" class="w-4 h-4 text-primary rounded focus:ring-primary">
                </div>
                <hr class="border-border">
                <div class="flex items-center justify-between">
                    <div>
                        <label class="text-sm font-medium">Exam Schedule Updates</label>
                        <p class="text-sm text-muted-foreground">Notify about exam schedule changes</p>
                    </div>
                    <input type="checkbox" checked class="w-4 h-4 text-primary rounded focus:ring-primary">
                </div>
                <button type="button" @click="handleSaveSettings()" :disabled="isLoading" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90 transition-colors disabled:opacity-50">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span x-show="!isLoading">Save Preferences</span>
                        <span x-show="isLoading">Saving...</span>
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- Security Settings -->
    <div x-show="activeTab === 'security'" class="space-y-6">
        <div class="bg-card rounded-xl p-6 shadow-sm border border-border/50">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <h3 class="text-lg font-semibold">Security Settings</h3>
            </div>
            <p class="text-sm text-muted-foreground mb-6">Manage your account security and access controls</p>
            
            <form @submit.prevent="handleSaveSettings()" class="space-y-6">
                <div x-show="successMessage" class="p-3 bg-green-100 border border-green-400 text-green-700 rounded-md text-sm" x-text="successMessage"></div>
                <div x-show="errorMessage" class="p-3 bg-red-100 border border-red-400 text-red-700 rounded-md text-sm" x-text="errorMessage"></div>
                <div class="space-y-2">
                    <label for="current-password" class="text-sm font-medium">Current Password</label>
                    <input type="password" id="current-password" placeholder="Enter current password" 
                           class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                </div>
                <div class="space-y-2">
                    <label for="new-password" class="text-sm font-medium">New Password</label>
                    <input type="password" id="new-password" placeholder="Enter new password" 
                           class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                </div>
                <div class="space-y-2">
                    <label for="confirm-password" class="text-sm font-medium">Confirm New Password</label>
                    <input type="password" id="confirm-password" placeholder="Confirm new password" 
                           class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                </div>
                <hr class="border-border">
                <div class="flex items-center justify-between">
                    <div>
                        <label class="text-sm font-medium">Two-Factor Authentication</label>
                        <p class="text-sm text-muted-foreground">Add an extra layer of security to your account</p>
                    </div>
                    <input type="checkbox" class="w-4 h-4 text-primary rounded focus:ring-primary">
                </div>
                <hr class="border-border">
                <div class="flex items-center justify-between">
                    <div>
                        <label class="text-sm font-medium">Session Timeout</label>
                        <p class="text-sm text-muted-foreground">Automatically log out after inactivity</p>
                    </div>
                    <select class="w-40 px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        <option value="15">15 minutes</option>
                        <option value="30" selected>30 minutes</option>
                        <option value="60">1 hour</option>
                        <option value="120">2 hours</option>
                    </select>
                </div>
                <button type="submit" :disabled="isLoading" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90 transition-colors disabled:opacity-50">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span x-show="!isLoading">Update Security</span>
                        <span x-show="isLoading">Updating...</span>
                    </span>
                </button>
            </form>
        </div>
    </div>

    <!-- System Settings -->
    <div x-show="activeTab === 'system'" class="space-y-6">
        <div class="bg-card rounded-xl p-6 shadow-sm border border-border/50">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                </svg>
                <h3 class="text-lg font-semibold">System Settings</h3>
            </div>
            <p class="text-sm text-muted-foreground mb-6">Configure system-wide settings and maintenance</p>
            
            <div class="space-y-6">
                <div class="space-y-2">
                    <label for="backup-frequency" class="text-sm font-medium">Backup Frequency</label>
                    <select id="backup-frequency" class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        <option value="daily" selected>Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <label class="text-sm font-medium">Auto Backup</label>
                        <p class="text-sm text-muted-foreground">Automatically backup system data</p>
                    </div>
                    <input type="checkbox" checked class="w-4 h-4 text-primary rounded focus:ring-primary">
                </div>
                <hr class="border-border">
                <div class="space-y-2">
                    <label class="text-sm font-medium">Maintenance Mode</label>
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-muted-foreground">Temporarily disable access for maintenance</p>
                        <input type="checkbox" class="w-4 h-4 text-primary rounded focus:ring-primary">
                    </div>
                </div>
                <hr class="border-border">
                <div class="space-y-2">
                    <label class="text-sm font-medium">System Information</label>
                    <div class="rounded-md border p-4 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Version:</span>
                            <span class="font-medium">1.0.0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Last Backup:</span>
                            <span class="font-medium">2024-01-15 10:30 AM</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Database Size:</span>
                            <span class="font-medium">2.4 GB</span>
                        </div>
                    </div>
                </div>
                <button type="button" @click="createBackup()" :disabled="isBackingUp" class="bg-muted text-foreground px-4 py-2 rounded-md hover:bg-muted/80 transition-colors disabled:opacity-50">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                        </svg>
                        <span x-show="!isBackingUp">Create Backup Now</span>
                        <span x-show="isBackingUp">Creating Backup...</span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
@endsection
