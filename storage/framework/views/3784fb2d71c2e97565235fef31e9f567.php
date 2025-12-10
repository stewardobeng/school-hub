

<?php $__env->startSection('content'); ?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Profile Card -->
    <div class="lg:col-span-1">
        <div class="bg-card rounded-xl p-6 shadow-sm border border-border/50">
            <div class="text-center">
                <div class="flex justify-center mb-4">
                    <div class="relative">
                        <div class="w-32 h-32 border-4 border-primary rounded-full bg-primary text-primary-foreground flex items-center justify-center text-3xl font-semibold">
                            JD
                        </div>
                        <button class="absolute bottom-0 right-0 rounded-full w-10 h-10 bg-muted border-2 border-background flex items-center justify-center hover:bg-muted/80 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <h2 class="text-2xl font-bold mb-1">John Doe</h2>
                <p class="text-muted-foreground">Administrator</p>
            </div>
            <div class="mt-6 space-y-4">
                <div class="flex items-center gap-2 text-sm text-muted-foreground">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span>admin@school.edu</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-muted-foreground">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <span>+1 234-567-8900</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-muted-foreground">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>New York, USA</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-muted-foreground">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>Joined: January 2020</span>
                </div>
                <hr class="border-border">
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-muted-foreground">Role:</span>
                        <span class="font-medium">Administrator</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-muted-foreground">Department:</span>
                        <span class="font-medium">Administration</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-muted-foreground">Status:</span>
                        <span class="font-medium text-green-600">Active</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Form -->
    <div class="lg:col-span-2">
        <div class="bg-card rounded-xl p-6 shadow-sm border border-border/50">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <h3 class="text-lg font-semibold">Edit Profile</h3>
            </div>
            <p class="text-sm text-muted-foreground mb-6">Update your personal information and preferences</p>
            
            <form class="space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label for="first-name" class="text-sm font-medium">First Name</label>
                        <input type="text" id="first-name" placeholder="John" value="John" 
                               class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                    </div>
                    <div class="space-y-2">
                        <label for="last-name" class="text-sm font-medium">Last Name</label>
                        <input type="text" id="last-name" placeholder="Doe" value="Doe" 
                               class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                    </div>
                </div>
                <div class="space-y-2">
                    <label for="email" class="text-sm font-medium">Email</label>
                    <input type="email" id="email" placeholder="admin@school.edu" value="admin@school.edu" 
                           class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                </div>
                <div class="space-y-2">
                    <label for="phone" class="text-sm font-medium">Phone</label>
                    <input type="tel" id="phone" placeholder="+1 234-567-8900" value="+1 234-567-8900" 
                           class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                </div>
                <div class="space-y-2">
                    <label for="address" class="text-sm font-medium">Address</label>
                    <input type="text" id="address" placeholder="123 Main Street, City, State" 
                           class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                </div>
                <div class="space-y-2">
                    <label for="bio" class="text-sm font-medium">Bio</label>
                    <textarea id="bio" placeholder="Tell us about yourself..." rows="4"
                              class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">Experienced administrator with a passion for education and student success.</textarea>
                </div>
                <hr class="border-border">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Change Password</h3>
                    <div class="space-y-4">
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
                    </div>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90 transition-colors">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Save Changes
                        </span>
                    </button>
                    <button type="button" class="bg-muted text-foreground px-4 py-2 rounded-md hover:bg-muted/80 transition-colors">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', ['title' => 'Profile', 'userRole' => 'admin'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\school-hub\resources\views/admin/profile.blade.php ENDPATH**/ ?>